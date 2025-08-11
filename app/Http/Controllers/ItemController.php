<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::paginate(15);
        return view('items.index', compact('items'));
    }

    public function create()
    {
        return view('items.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:Allowed,Prohibited',
        ]);

        Item::create($request->all());

        return redirect()->route('items.index')
            ->with('success', 'Элемент успешно создан.');
    }

    public function show(Item $item)
    {
        return view('items.show', compact('item'));
    }

    public function edit(Item $item)
    {
        return view('items.edit', compact('item'));
    }

    public function update(Request $request, Item $item): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:Allowed,Prohibited',
        ]);

        $item->update($request->all());

        return redirect()->route('items.index')
            ->with('success', 'Элемент успешно обновлён.');
    }

    public function destroy(Item $item): RedirectResponse
    {
        $item->delete();

        return redirect()->route('items.index')
            ->with('success', 'Элемент успешно удалён.');
    }

    public function generate(): RedirectResponse
    {
        Item::factory()->count(1000)->create();

        return redirect()->route('items.index')
            ->with('success', 'Сгенерировано 1000 элементов.');
    }

    public function clear(): RedirectResponse
    {
        Item::truncate();

        return redirect()->route('items.index')
            ->with('success', 'Таблица очищена.');
    }
}
