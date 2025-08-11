<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Показать элемент</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 p-8">

<div class="container mx-auto">
    <h1 class="text-3xl font-bold mb-6">Детали элемента</h1>

    <div class="bg-white shadow-md rounded-lg p-6">
        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">ID:</label>
            <p class="text-gray-900">{{ $item->id }}</p>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Название:</label>
            <p class="text-gray-900">{{ $item->name }}</p>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Описание:</label>
            <p class="text-gray-900">{{ $item->description }}</p>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Статус:</label>
            <p class="text-gray-900">{{ $item->status }}</p>
        </div>
        <a href="{{ route('items.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Назад к списку</a>
    </div>
</div>

</body>
</html>
