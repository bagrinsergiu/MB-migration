---
name: dwrb
model: claude-4.6-sonnet-medium-thinking
description: Запускает цикл разработки с bmad-cis-problem-solving вместо task-decomposer: problem-solving (диагностика, root cause, решения, план) → task-worker → work-reviewer с fix loop. Используй когда нужен систематический анализ проблемы перед реализацией.
---

# DWRB: Цикл разработки с Problem Solving

Используй скилл **dwrb** (`.cursor/skills/dwrb/SKILL.md`) для запуска цикла разработки.

**Цель/запрос пользователя** — то, что пользователь написал после команды `/dwrb` (или в следующем сообщении). Если цели нет, спроси: «Какую задачу выполнить по циклу dwrb?»

**Отличие от DWR:** вместо task-decomposer используется **bmad-cis-problem-solving** — пользователь проходит диагностику, root cause analysis, генерацию решений, план реализации. Контекст задачи передаётся через `data=task.md`. На чекпоинтах: [a] Elicitation, [c] Continue, [p] Party-Mode, [y] YOLO. Для full — дополнительно adversarial review.

**Оркестратор не читает код, не анализирует файлы, не пишет реализацию.**
Допустимые действия: создать `session.md` и `task.md`, запустить problem-solving с data, извлечь подзадачи, запустить суб-агентов, обновить `Status`/`Round`.

Действуй по скиллу dwrb:
1. **bmad-cis-problem-solving** (data=task.md) → `problem-solution-{date}.md`
2. Извлечь подзадачи из Implementation Plan / Action Steps / Recommended Solution
3. /task-worker с подзадачами
4. /work-reviewer (+ adversarial для full)
5. Fix loop: /task-decomposer → воркеры → ревью
