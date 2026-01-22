#!/bin/bash

echo "🔍 Проверка состояния дашборда..."
echo ""

# Проверка API
echo "1. Проверка Backend API..."
API_RESPONSE=$(curl -s http://localhost:8000/dashboard/api/health 2>&1)
if echo "$API_RESPONSE" | grep -q "success"; then
    echo "   ✅ API работает"
else
    echo "   ❌ API не доступен на http://localhost:8000/dashboard/api"
    echo "   Запустите PHP сервер на порту 8000"
    exit 1
fi

# Проверка зависимостей фронтенда
echo ""
echo "2. Проверка зависимостей фронтенда..."
cd dashboard/frontend
if [ -d "node_modules" ]; then
    echo "   ✅ node_modules существует"
else
    echo "   ⚠️  node_modules не найден, устанавливаю зависимости..."
    npm install
fi

# Проверка новых компонентов
echo ""
echo "3. Проверка новых компонентов..."
if [ -f "src/components/QualityAnalysis.tsx" ]; then
    echo "   ✅ QualityAnalysis.tsx найден"
    NEED_REBUILD=true
else
    echo "   ⚠️  QualityAnalysis.tsx не найден"
fi

# Проверка сборки
echo ""
echo "4. Проверка сборки фронтенда..."
if [ -d "dist" ] && [ -f "dist/index.html" ]; then
    echo "   ✅ Фронтенд собран"
    if [ "$NEED_REBUILD" = true ]; then
        echo "   ⚠️  Обнаружены новые компоненты, требуется пересборка"
        echo ""
        read -p "Пересобрать фронтенд? (y/n) " -n 1 -r
        echo
        if [[ $REPLY =~ ^[Yy]$ ]]; then
            echo "   🔨 Сборка фронтенда..."
            npm run build
            echo "   ✅ Сборка завершена"
        fi
    fi
else
    echo "   ⚠️  Фронтенд не собран"
    echo "   🔨 Сборка фронтенда..."
    npm run build
    echo "   ✅ Сборка завершена"
fi

echo ""
echo "✅ Проверка завершена!"
echo ""
echo "📋 Следующие шаги:"
echo "   1. Откройте браузер: http://localhost:8000/dashboard"
echo "   2. Выберите миграцию"
echo "   3. Перейдите на вкладку 'Анализ'"
echo ""
echo "💡 Для разработки с hot reload:"
echo "   cd dashboard/frontend && npm run dev"
echo "   Затем откройте: http://localhost:3000"
echo ""
echo "⚠️  Важно: Migration API работает на порту 8080"
echo "   Dashboard работает на порту 8000"