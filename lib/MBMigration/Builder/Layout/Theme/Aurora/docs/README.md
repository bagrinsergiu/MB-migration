# Aurora Theme Documentation

## Обзор

Aurora - тема для миграции контента из Clover Sites в Brizy Builder. Тема использует **Playwright** для извлечения стилей из DOM исходного сайта и предоставляет специализированные элементы для преобразования структуры и стилей.

## Ключевые особенности

### Использование Playwright для извлечения стилей

Тема активно использует Playwright (`BrowserPageInterface`) для получения вычисленных CSS-стилей из DOM:
- Извлечение стилей через `browserPage->evaluateScript('brizy.getStyles', ...)`
- Получение стилей с псевдоэлементов (`::before`, `::after`)
- Извлечение стилей из динамических элементов (слайды, аккордеоны, вкладки)

### Обработка фона страницы и градиентов

Тема автоматически обрабатывает градиенты и цвета фона страницы через метод `beforeBuildPage()`:

- Извлекает стили фона из `body` элемента через Playwright
- Определяет тип фона: градиент или сплошной цвет
- Преобразует градиенты в структурированный формат для Brizy
- Fallback на белый цвет при ошибках
- **Важно**: Цвет фона с `body` используется как fallback для элементов, у которых нет собственного фона

#### Процесс обработки градиента

1. **Извлечение градиента** (`Aurora::beforeBuildPage()`):
   - Использует Playwright для получения `background-image` и `background-color` из `body`
   - Парсит CSS градиент через класс `Gradient`
   - Возвращает структурированный массив с типом, углом и цветами

2. **Передача градиента**:
   - Градиент сохраняется в `pageDTO->setPageStyleDetails()`
   - Доступен через `$data->getThemeContext()->getPageDTO()->getPageStyleDetails()['bg-gradient']`

3. **Применение к секциям**:
   - В `handleSectionStyles()` градиент добавляется в `$sectionStyles['bg-gradient']`
   - Метод `handleSectionGradient()` применяет градиент к `BrizyComponent`
   - Устанавливаются параметры: тип, угол, цвета, проценты, мобильные версии

4. **Унификация в элементах**:
   - Все элементы с `afterTransformItem()` используют единый подход
   - Получают градиент из `pageStyleDetails`
   - Проверяют наличие градиента перед применением
   - Используют `handleSectionGradient()` для установки градиента
   - Устанавливают `gradientActivePointer` для корректной работы

### Селекторы элементов

**Иконки:**
```php
getThemeIconSelector(): "[data-socialicon],[style*=\"font-family: 'Mono Social Icons Font'\"],[data-icon]"
```

**Кнопки:**
```php
getThemeButtonSelector(): ".sites-button:not(.nav-menu-button)"
```

### TypeScript Assets

Тема включает TypeScript модули для DOM-манипуляций:
- `Accordion` - обработка аккордеонов
- `Dom` - базовые DOM операции
- `Menu` - обработка меню
- `StyleExtractor` - извлечение стилей
- `Tabs` - обработка вкладок
- `Text` - обработка текстовых элементов

## Структура элементов

### Text Elements
- `FullText` - полный текст
- `FullTextBlurBox` - текст с эффектом blur-box overlay
- `FullMediaElement` - медиа с текстом
- `LeftMedia`, `RightMedia` - медиа слева/справа
- `LeftMediaCircle`, `RightMediaCircle` - медиа с круглыми элементами
- `TwoHorizontalText` - два горизонтальных текста
- `TwoRightMediaCircle` - два медиа справа
- `ThreeTopMediaCircle` - три медиа сверху
- `GridLayoutElement` - сетка элементов
- `ListLayoutElement` - список элементов
- `TabsLayoutElement` - вкладки
- `AccordionLayoutElement` - аккордеон

### Forms
- `Form` - полная ширина формы
- `LeftForm` - форма слева
- `RightForm` - форма справа

### Gallery
- `GalleryLayoutElement` - галерея изображений
  - Извлекает стили каждого слайда из DOM через Playwright
  - Применяет стили (height, padding, margin, border-radius) только к Column (картинке)
  - Не изменяет бэграунд секции - использует стандартную логику из `SectionStylesAble`
  - Подробности: см. [GalleryLayoutElement.md](./GalleryLayoutElement.md)

### Events
- `EventLayoutElement` - список событий
- `EventGalleryLayout` - галерея событий
- `EventListLayout` - список событий
- `EventTileLayout` - плитка событий

### Sermons
- `MediaLayoutElement` - медиа-контент
- `GridMediaLayout` - сетка медиа
- `ListMediaLayout` - список медиа

### Prayer
- `PrayerFormElement` - форма молитвы
- `PrayerList` - список молитв

### Groups
- `SmallGroupsListElement` - список малых групп

### Navigation
- `Head` - шапка сайта с меню
- `Footer` - подвал сайта

## Важные методы

### beforeBuildPage()
Обрабатывает фон страницы перед построением, извлекая градиенты или цвета из `body` элемента.

### getThemeIconSelector()
Возвращает CSS селектор для поиска иконок социальных сетей.

### getThemeButtonSelector()
Возвращает CSS селектор для поиска кнопок (исключая кнопки меню).

## Особенности миграции

- **Playwright для извлечения стилей**: Все стили извлекаются из DOM через Playwright, что позволяет получить актуальные вычисленные значения
- **Fallback на body background**: Элементы без собственного фона получают цвет фона с `body` элемента
- **Унифицированная обработка градиентов**: Все элементы темы используют единый подход для применения градиентов из `body` элемента
- Автоматическое определение типа фона (градиент/цвет)
- Специализированная обработка blur-box элементов с поддержкой псевдоэлементов
- Извлечение стилей из DOM через TypeScript модули
- Нормализация цветов и значений через утилиты (`ColorConverter`, `NumberProcessor`)
- **Переопределение методов**: Тема переопределяет методы абстрактного класса для специфичной логики (например, `GalleryLayoutElement` переопределяет `setSlideImage` и `applySlideStyles`)

## Детали разработки

### Обработка градиентов в элементах

Все элементы темы Aurora, которые используют `afterTransformItem()` для установки фона, следуют единому принципу:

**Шаблон для `afterTransformItem()`:**

```php
protected function afterTransformItem(ElementContextInterface $data, BrizyComponent $brizySection): void
{
    $mbSectionItem = $data->getMbSection();
    $selectId = $mbSectionItem['id'] ?? $mbSectionItem['sectionId'];

    $sectionSelector = '[data-id="' .$selectId. '"] .bg-helper>.bg-opacity';
    $styles = $this->browserPage->evaluateScript('brizy.getStyles', [...]);

    // Получаем градиент из дополнительных опций, если он есть
    $additionalOptions = $data->getThemeContext()->getPageDTO()->getPageStyleDetails();
    if (!empty($additionalOptions['bg-gradient'])) {
        $styles['data']['bg-gradient'] = $additionalOptions['bg-gradient'];
    }

    // Устанавливаем градиент или цвет фона на SectionItem
    $sectionItemComponent = $this->getSectionItemComponent($brizySection);
    if (!empty($styles['data']['bg-gradient'])) {
        $this->handleSectionGradient($sectionItemComponent, $styles['data']);
        $sectionItemComponent->getValue()->set('gradientActivePointer', 'finishPointer');
    } else {
        $this->handleItemBackground($sectionItemComponent, $styles['data']);
    }
}
```

**Элементы с унифицированной обработкой градиентов:**
- `FullText` - полный текст
- `FullTextBlurBox` - текст с blur-box overlay
- `FullMediaElement` - медиа с текстом
- `GridLayoutElement` - сетка элементов
- `EventLayoutElement` - список событий

**Ключевые моменты:**
1. Градиент извлекается из `pageStyleDetails` (результат `beforeBuildPage()`)
2. Проверка наличия градиента перед применением
3. Использование `getSectionItemComponent()` для получения правильного компонента
4. Установка `gradientActivePointer` для корректной работы градиента в Brizy

### Поток обработки градиента

```
1. Aurora::beforeBuildPage()
   └─> Playwright: body styles
   └─> Gradient::parseGradient()
   └─> return ['bg-gradient' => [...]]
   └─> pageDTO->setPageStyleDetails()

2. Element::transformToItem()
   └─> handleSectionStyles()
       └─> additionalOptions['bg-gradient'] из pageDTO
       └─> $sectionStyles['bg-gradient'] = additionalOptions['bg-gradient']

3. handleSectionBackground()
   └─> handleItemBackground() [устанавливает solid как базовый]
       └─> handleSectionGradient() [переопределяет на gradient]

4. afterTransformItem() [для элементов с этим методом]
   └─> Получает градиент из pageStyleDetails
   └─> handleSectionGradient() [применяет к SectionItem]
```

## Важные переопределения методов

### GalleryLayoutElement
- `handleStyle()` - извлечение стилей слайдов из DOM через Playwright (height, padding, margin, border-radius)
- `normalizeSlideStyles()` - нормализация стилей слайдов с fallback на стили секции
- `applySlideStyles()` - применение стилей только к Column (картинке), не к SectionItem
- **Не переопределяет**: `handleSectionStyles()`, `setSlideImage()` - используется стандартная логика

**Важно**: Бэграунд секции работает стандартно через `handleSectionStyles()` из `SectionStylesAble`. Стили применяются только к картинкам (Column), не к слайдеру (SectionItem).

Подробности: см. [GalleryLayoutElement.md](./GalleryLayoutElement.md)
