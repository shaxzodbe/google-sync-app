<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Список элементов</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 p-8">

<div class="container mx-auto">
    <h1 class="text-3xl font-bold mb-6">Список элементов</h1>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-6 flex space-x-4">
        <a href="{{ route('items.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Создать новый элемент</a>
        <form action="{{ route('items.generate') }}" method="POST">
            @csrf
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Сгенерировать 1000 строк</button>
        </form>
        <form action="{{ route('items.clear') }}" method="POST" onsubmit="return confirm('Вы уверены, что хотите очистить таблицу?')">
            @csrf
            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Очистить таблицу</button>
        </form>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Настройки Google Таблицы</h2>
        <form action="{{ route('settings.update') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="google_sheet_url" class="block text-gray-700 font-bold mb-2">URL Google Таблицы</label>
                <input type="text" name="google_sheet_url" id="google_sheet_url" value="{{ config('services.google.sheet_id') ? 'https://docs.google.com/spreadsheets/d/' . config('services.google.sheet_id') : '' }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Сохранить URL</button>
        </form>
    </div>

    <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
        <thead class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
        <tr>
            <th class="py-3 px-6 text-left">ID</th>
            <th class="py-3 px-6 text-left">Название</th>
            <th class="py-3 px-6 text-left">Статус</th>
            <th class="py-3 px-6 text-left">Дата создания</th>
            <th class="py-3 px-6 text-center">Действия</th>
        </tr>
        </thead>
        <tbody class="text-gray-600 text-sm font-light">
        @foreach ($items as $item)
            <tr class="border-b border-gray-200 hover:bg-gray-100">
                <td class="py-3 px-6 text-left whitespace-nowrap">{{ $item->id }}</td>
                <td class="py-3 px-6 text-left">{{ $item->name }}</td>
                <td class="py-3 px-6 text-left">
                        <span class="py-1 px-3 rounded-full text-xs font-semibold {{ $item->status === 'Allowed' ? 'bg-green-200 text-green-600' : 'bg-red-200 text-red-600' }}">
                            {{ $item->status }}
                        </span>
                </td>
                <td class="py-3 px-6 text-left">{{ $item->created_at->format('Y-m-d H:i') }}</td>
                <td class="py-3 px-6 text-center">
                    <div class="flex item-center justify-center space-x-2">
                        <a href="{{ route('items.show', $item) }}" class="bg-gray-200 p-2 rounded-full hover:bg-gray-300">👁️</a>
                        <a href="{{ route('items.edit', $item) }}" class="bg-gray-200 p-2 rounded-full hover:bg-gray-300">✏️</a>
                        <form action="{{ route('items.destroy', $item) }}" method="POST" onsubmit="return confirm('Вы уверены, что хотите удалить этот элемент?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-gray-200 p-2 rounded-full hover:bg-gray-300">🗑️</button>
                        </form>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $items->links() }}
    </div>
</div>

</body>
</html>
