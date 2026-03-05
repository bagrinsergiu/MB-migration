---
name: dwrb
description: Runs the development cycle with bmad-cis-problem-solving instead of task-decomposer: problem-solving (diagnosis, root cause, solutions, implementation plan) → task-worker → work-reviewer with fix loop. Use when the user wants systematic problem analysis before implementation, or when they say "dwrb", "цикл с problem solving".
---

# DWRB: Цикл разработки с Problem Solving

Запускает полный цикл, где **вместо task-decomposer** используется **bmad-cis-problem-solving**. Пользователь проходит диагностику проблемы, root cause analysis, генерацию решений и план реализации. Результат (`problem-solution-{date}.md`) становится источником подзадач для воркеров.

Подзадачи из problem-solving выполняются **параллельно** по волнам (аналогично DWR). Fix loop использует **task-decomposer** (превращение замечаний ревью в задачи).

## Роль оркестратора: только управление (строго)

Оркестратор выполняет **только**:
1. Создаёт/обновляет `session.md`, `task.md`.
2. Запускает problem-solving workflow (шаг 1) или суб-агентов (шаги 2–4).
3. Извлекает подзадачи из `problem-solution-{date}.md` и формирует `decomp_r0.md`.
4. Читает артефакты и мержит их в `session.md`.

### ЗАПРЕЩЕНО оркестратору

- Читать файлы кодовой базы (кроме parsing problem-solution)
- Искать код или символы
- Анализировать архитектуру
- Писать реализацию
- Проводить ревью кода

## Когда применять

- Пользователь хочет «цикл с аналитикой» / «dwrb» / «problem solving перед кодом»
- Задача сложная или неясная — нужна диагностика перед реализацией
- Нужно пройти: problem-solving → реализация → ревью

## Порядок выполнения

---

### Шаг 0: Создание директории сессии

Аналогично DWR:

1. Определить следующий номер: `task/` → max(NNN) + 1 (или 001).
2. Создать `task/{NNN}_{YYYY-MM-DD_HHmmss}/`.
3. Записать `task.md` с исходной задачей пользователя.
4. Создать `session.md`:

```markdown
# Session {NNN}_{datetime}

## Task
[исходная формулировка]

## Status
in_progress

## Round
0
```

---

### Шаг 1: Запуск «bmad-cis-problem-solving» — систематический анализ проблемы

**Выполнить workflow problem-solving** (в текущем контексте агента):

1. Загрузить **полностью** `@{project-root}/_bmad/core/tasks/workflow.xml`.
2. Прочитать его содержимое.
3. Передать `workflow-config` = `@{project-root}/_bmad/cis/workflows/problem-solving/workflow.yaml`.
4. **Передать data attribute** = `task/{NNN}_{datetime}/task.md` — контекст задачи для первого шага workflow (workflow загрузит его при «Load any context data provided via the data attribute»).
5. Следовать инструкциям workflow.xml для выполнения problem-solving workflow.

**Вход для workflow:** Исходная задача из `task.md` — начальная формулировка проблемы. При вопросе «What problem are you trying to solve?» использовать её (при наличии data — контекст уже загружен).

**Результат:** файл `_bmad-output/problem-solution-{date}.md` (дата в формате YYYY-MM-DD).

**Чекпоинты workflow:** После каждого `template-output` workflow.xml предлагает:
- **[a] Advanced Elicitation** — углубить секцию через elicitation-методы (`_bmad/core/workflows/advanced-elicitation/workflow.xml`).
- **[c] Continue** — продолжить к следующему шагу.
- **[p] Party-Mode** — обсуждение с несколькими BMAD-агентами (`_bmad/core/workflows/party-mode/workflow.md`).
- **[y] YOLO** — пропустить все подтверждения до конца workflow, завершить автоматически (режим неинтерактивный/быстрый).

Дождаться завершения workflow. Сохранять вывод после каждого шага. После шага 9 или при завершении — перейти к шагу 1б.

---

### Шаг 1б: Извлечение подзадач из problem-solution

Оркестратор читает `_bmad-output/problem-solution-{date}.md` и формирует `task/{NNN}_{datetime}/decomp_r0.md` в формате, совместимом с DWR.

**Источники подзадач (по приоритету):**
- **Implementation Plan > Action Steps** — каждый пункт списка = подзадача.
- **Implementation Plan > Changes Made** — каждая строка таблицы (Task + Change) = подзадача.
- **Recommended Solution** — если Action Steps / Changes Made пусты, каждый пункт списка = подзадача.

**Формат decomp_r0.md:**

```
## Complexity
lite | full
Причина: из problem-solving; {N} подзадач, {F} файлов
```

- **lite:** ≤5 подзадач, 1–2 файла.
- **full:** 6+ подзадач или 3+ файлов.

```
## Подзадачи

**Подзадача 1: [название из Action Step / Changes Made]**
- Зависимости: нет
- Критерий: [из Success Criteria или сформулировать по контексту]
- Файлы: [из Problem Context / Implementation Plan]

**Подзадача 2: ...**
...
```

```
## Волны

**Волна 1 (параллельно):** Подзадачи 1, 2, ... — [все без зависимостей или одна волна по умолчанию]
```

По умолчанию: **одна волна** со всеми подзадачами, если в документе не указаны явные зависимости. Если разные подзадачи затрагивают один файл — группировать в одну волну (один воркер получает список изменений для файла).

После формирования decomp_r0.md:
- Добавить в `session.md` секцию `## Decomposition Round 0` (краткое резюме).
- Обновить `Status` → `in_progress`.

---

### Routing Gate (после decomp_r0.md — обязательно)

Читать `## Complexity` из decomp_r0.md и выбрать ветку:

| Complexity | Ветка | Модель | Fix loop |
|---|---|---|---|
| nano | Шаг 2-nano + Шаг 3-adversarial | fast | нет |
| lite | Шаг 2 + Шаг 3-lite + Шаг 3-adversarial | fast | max 1, только CRITICAL |
| full | Шаг 2 + Шаг 3 + Шаг 3-adversarial | default | до 3 раундов |

Если `## Complexity` отсутствует — считать `full`.

**Single-file downgrade:** Если `full`, но все подзадачи в одном файле → понизить до `lite`.

---

### Шаг 2-nano: Выполнение с adversarial review (только для nano)

Запустить 1 воркера (model: fast) с единственной подзадачей. После завершения — обновить session, перейти к **Шаг 3-adversarial** (model: fast), затем к **Завершению цикла** (без work-reviewer).

---

### Шаг 2: Параллельный запуск «task-worker» — реализация по волнам

Аналогично DWR: для каждой волны из decomp_r0.md — запустить воркеры **одновременно**, ждать завершения, применить **Wave Gate** (BLOCKED, NOT MET, Discoveries, merge → session.md).

**При BLOCKED:** Запустить task-decomposer с задачей уточнения (max 1 mid-cycle re-decomposition).

**При NOT MET:** Передать в fix loop.

Передавать воркерам:
- Подзадачу, критерий, файлы из decomp_r0.md.
- Директорию сессии, путь к артефакту `impl_r0_w{W}_t{K}.md`.

---

### Шаг 3: Запуск «work-reviewer» — проверка результата

Аналогично DWR. Суб-агент: `.cursor/agents/work-reviewer.md`. Результат → `review_r0.md`.

---

### Шаг 3-adversarial: Adversarial Review (все уровни сложности)

После work-reviewer (или после шага 2-nano) запустить **дополнительный** adversarial pass через команду `/bmad-review-adversarial-general`:

1. Запустить команду `/bmad-review-adversarial-general` в **отдельном суб-агенте** (без контекста кодовой базы, только контент для ревью).
2. **Вход:** `content` = дифф/изменённые файлы из реализации; `also_consider` = «исходная задача: [task.md], критерии из problem-solution».
3. **Результат:** список находок (минимум 10). Добавить в `review_r0.md` секцию `## Adversarial Findings`.

**Adversarial findings НЕ идут автоматически в fix loop.** Оркестратор принимает решение вручную:
- Видит оба отчёта (`work-reviewer` + adversarial).
- Сам решает, какие adversarial findings передать в fix loop.
- Передаёт выбранные findings в task-decomposer с флагом `Режим: adversarial` (см. Fix loop ниже).

Задача adversarial — скептический взгляд «со стороны», находит скрытые проблемы. Не заменяет work-reviewer, дополняет его.

---

### Шаг 3-lite: Ревью для lite

При `Complexity: lite` — work-reviewer с `model: fast`, затем **Шаг 3-adversarial** (model: fast). Fix loop только при CRITICAL, max 1 раунд.

---

### Fix loop (при критичных/важных замечаниях)

Применяется для `Complexity: full` (и lite при CRITICAL, 1 раунд).

**Шаг 3а:** Запустить **task-decomposer** с замечаниями на исправление. Результат → `decomp_r{N+1}.md`.

Источники замечаний (передавать вместе):
- Замечания `work-reviewer` из `review_r{N}.md` (всегда).
- Adversarial findings, которые оркестратор решил включить (передавать с флагом `Режим: adversarial`).

Когда adversarial findings переданы с флагом `Режим: adversarial`, task-decomposer активирует специальный режим обработки: фильтрует шум, локализует в коде, группирует по файлу (одна подзадача = один файл = один воркер).

**Шаг 3б:** Запустить воркеров по волнам из decomp_r{N+1}.md.

Далее — снова **Шаг 3** (work-reviewer) и при full — **Шаг 3-adversarial**. Повторять до прохождения или лимита раундов (3).

---

### Завершение цикла

- Если замечаний нет или достигнут лимит: обновить `Status` → `completed` / `awaiting_next` / `escalated`.
- Итог: что выполнено, путь к сессии `task/{NNN}_{datetime}/`.

---

## Краткая схема

```
[Запрос пользователя]
        ↓
   [0. Создать task/{NNN}_{datetime}/, task.md, session.md]
        ↓
   [1. bmad-cis-problem-solving workflow] → _bmad-output/problem-solution-{date}.md
        ↓
   [1б. Извлечь подзадачи → decomp_r0.md]
        ↓
   [Routing Gate: Complexity]
        ↓
   [2. task-worker по волнам] → impl_r0_w*_t*.md
        ↓
   [3. work-reviewer] → review_r0.md
        ↓
   [3-adversarial] → /bmad-review-adversarial-general → ## Adversarial Findings в review_r0.md
        ↓
   Оркестратор: вручную выбирает findings для fix loop
        ↓
   → [3а. task-decomposer (Режим: adversarial + work-reviewer)] → [3б. workers] → [3. reviewer] (fix loop)
        ↓
   [Завершение]
```

---

## Важно

- **Шаг 1** — problem-solving выполняется **интерактивно** в текущем агенте. Передать `data=task/{NNN}_{datetime}/task.md` для предзагрузки контекста. На чекпоинтах доступны [a] Elicitation, [c] Continue, [p] Party-Mode, [y] YOLO.
- **Шаг 1б** — оркестратор **парсит** problem-solution и создаёт decomp_r0.md вручную.
- **Шаг 3-adversarial** — для **всех уровней сложности**; запускает `/bmad-review-adversarial-general` в отдельном суб-агенте. Дополняет work-reviewer скептическим взглядом. **Оркестратор вручную решает**, какие adversarial findings идут в fix loop.
- **Fix loop** использует **task-decomposer**. Adversarial findings передаются с флагом `Режим: adversarial` — декомпозитор фильтрует шум, локализует в коде, группирует по файлу (минимум воркеров).
- Остальные правила (Wave Gate, артефакты, session.md) — как в DWR.
