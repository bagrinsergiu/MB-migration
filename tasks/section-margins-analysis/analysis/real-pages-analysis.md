# Анализ реальных страниц

## Проверенные сайты

1. lifespringhill.org
2. c3forchrist.org
3. camptekakwitha.org (планируется)

---

## lifespringhill.org

### Общая информация
- **URL:** https://lifespringhill.org
- **Количество секций:** 4
- **Тип секций:** `article[data-id]`

### Стили секций

#### Секция 1 (data-id: 1346553)
- `marginTop`: 0px
- `marginBottom`: 0px
- `marginLeft`: 0px
- `marginRight`: 0px
- `paddingTop`: 43.5px
- `paddingBottom`: 43.5px
- `paddingLeft`: 30px
- `paddingRight`: 30px

#### Секция 2 (data-id: 5471863)
- `marginTop`: -1px
- `marginBottom`: 0px
- `marginLeft`: 0px
- `marginRight`: 0px
- `paddingTop`: 8.5px
- `paddingBottom`: 8.5px
- `paddingLeft`: 30px
- `paddingRight`: 30px

#### Секция 3 (data-id: 5293313)
- `marginTop`: -1px
- `marginBottom`: 0px
- `marginLeft`: 0px
- `marginRight`: 0px
- `paddingTop`: 24.5px
- `paddingBottom`: 24.5px
- `paddingLeft`: 30px
- `paddingRight`: 30px

#### Секция 4 (data-id: 5548500)
- `marginTop`: -1px
- `marginBottom`: 0px
- `marginLeft`: 0px
- `marginRight`: 0px
- `paddingTop`: 13px
- `paddingBottom`: 13px
- `paddingLeft`: 30px
- `paddingRight`: 30px

### Расстояния между секциями

- **Между секцией 1 и 2:** -1px (перекрытие)
- **Между секцией 2 и 3:** -1px (перекрытие)
- **Между секцией 3 и 4:** -1px (перекрытие)

### Выводы

1. **Margin'ы между секциями:** Практически отсутствуют (0px или -1px)
2. **Отступы реализованы через:** Padding внутри секций
3. **Секции перекрываются:** На 1px из-за `marginTop: -1px`

---

## c3forchrist.org

### Общая информация
- **URL:** https://c3forchrist.org
- **Количество секций:** 6
- **Тип секций:** `article[data-id]`

### Стили секций

#### Секция 1 (data-id: 5450721)
- `marginTop`: 0px
- `marginBottom`: 0px
- `paddingTop`: 0px
- `paddingBottom`: 0px

#### Секция 2 (data-id: 3838284)
- `marginTop`: 0px
- `marginBottom`: 0px
- `paddingTop`: 75px
- `paddingBottom`: 75px

#### Секция 3 (data-id: 3840388)
- `marginTop`: 0px
- `marginBottom`: 0px
- `paddingTop`: 73.5px
- `paddingBottom`: 73.5px

#### Секция 4 (data-id: 4438000)
- `marginTop`: 0px
- `marginBottom`: 0px
- `paddingTop`: 120px
- `paddingBottom`: 120px

#### Секция 5 (data-id: 4697623)
- `marginTop`: 0px
- `marginBottom`: 0px
- `paddingTop`: 120px
- `paddingBottom`: 120px

#### Секция 6 (data-id: 3838402)
- `marginTop`: 0px
- `marginBottom`: 0px
- `paddingTop`: 120px
- `paddingBottom`: 120px

### Расстояния между секциями

- **Между всеми секциями:** 0px (секции идут друг за другом без отступов)

### Выводы

1. **Margin'ы между секциями:** Полностью отсутствуют (0px)
2. **Отступы реализованы через:** Padding внутри секций
3. **Секции не перекрываются:** Идут друг за другом

---

## Общие выводы

### Как реализованы отступы в исходных сайтах

1. **Margin'ы между секциями:**
   - Все секции имеют `marginTop: "0px"` и `marginBottom: "0px"`
   - Секции идут друг за другом без отступов (или с минимальным перекрытием -1px)

2. **Визуальные отступы:**
   - Создаются через **padding внутри секций**
   - Разные секции имеют разные padding'ы
   - Padding'ы могут быть разными для разных сторон

3. **Структура:**
   - Секции являются прямыми соседями
   - Нет дополнительных оберток с отступами
   - Родительский элемент (main) не имеет padding'ов или margin'ов

### Что это означает для миграции

1. **Текущая реализация правильная:**
   - Мы правильно извлекаем `margin-top: 0px` и `margin-bottom: 0px`
   - Мы правильно применяем эти значения к секциям в Brizy

2. **Padding'ы важны:**
   - Padding'ы внутри секций создают визуальные отступы
   - Нужно правильно извлекать и применять padding'ы

3. **Потенциальные проблемы:**
   - Если в исходном сайте есть отступы между секциями через другие механизмы, мы их не учитываем
   - Нужно проверить, нет ли отступов через родительские элементы или обертки

---

## Следующие шаги

1. Проверить camptekakwitha.org
2. Проверить, нет ли отступов через другие механизмы
3. Сравнить с мигрированными страницами
