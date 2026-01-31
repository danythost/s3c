@extends('layouts.admin')

@section('title', 'Airtime Control')

@section('content')
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="text-2xl font-bold mb-1">VTU Services</h2>
        <div class="flex gap-4 text-sm">
            <a href="{{ route('admin.vtu.plans') }}" class="text-gray-400 hover:text-white transition-colors pb-1">Data Plans</a>
            <a href="{{ route('admin.vtu.airtime') }}" class="text-blue-400 font-bold border-b-2 border-blue-400 pb-1">Airtime Control</a>
        </div>
    </div>
</div>

<div class="glass rounded-3xl overflow-hidden mb-8">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-white/5 border-b border-white/10 uppercase text-[10px] tracking-wider text-gray-400">
                    <th class="p-6 font-bold">Network</th>
                    <th class="p-6 font-bold">Min Limit (₦)</th>
                    <th class="p-6 font-bold">Max Limit (₦)</th>
                    <th class="p-6 font-bold">Commission (%)</th>
                    <th class="p-6 font-bold">Status</th>
                    <th class="p-6 font-bold text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @foreach($controls as $control)
                    <tr class="hover:bg-white/5 transition-colors group">
                        <td class="p-6">
                            <span class="px-3 py-1 rounded-lg bg-white/5 text-xs font-bold">{{ $control->network }}</span>
                        </td>
                        <td class="p-6">
                            <input type="number" step="0.01" name="min_amount" value="{{ $control->min_amount }}" form="form-{{ $control->id }}"
                                   class="w-32 bg-[#0f172a] border border-white/10 rounded-lg px-3 py-2 text-sm focus:border-blue-500 outline-none">
                        </td>
                        <td class="p-6">
                            <input type="number" step="0.01" name="max_amount" value="{{ $control->max_amount }}" form="form-{{ $control->id }}"
                                   class="w-32 bg-[#0f172a] border border-white/10 rounded-lg px-3 py-2 text-sm focus:border-blue-500 outline-none">
                        </td>
                        <td class="p-6">
                            <div class="relative w-24">
                                <input type="number" step="0.01" name="commission_percentage" value="{{ $control->commission_percentage }}" form="form-{{ $control->id }}"
                                       class="w-full bg-[#0f172a] border border-white/10 rounded-lg pl-3 pr-6 py-2 text-sm focus:border-blue-500 outline-none">
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 text-xs">%</span>
                            </div>
                        </td>
                        <td class="p-6">
                            <label class="cursor-pointer relative inline-flex items-center">
                                <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ $control->is_active ? 'checked' : '' }} form="form-{{ $control->id }}">
                                <div class="w-9 h-5 bg-gray-600 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-600"></div>
                                <span class="ml-2 text-xs font-bold {{ $control->is_active ? 'text-emerald-400' : 'text-gray-500' }}">
                                    {{ $control->is_active ? 'Active' : 'Disabled' }}
                                </span>
                            </label>
                        </td>
                        <td class="p-6">
                            <form action="{{ route('admin.vtu.airtime.update', $control) }}" method="POST" id="form-{{ $control->id }}">
                                @csrf
                                @method('PATCH')
                                <div class="flex justify-center">
                                    <button type="submit" class="px-4 py-2 rounded-lg bg-blue-500/10 text-blue-400 hover:bg-blue-500 hover:text-white transition-all text-xs font-bold">
                                        Save
                                    </button>
                                </div>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Note on structure: Since wrapping TR/TDs in form is invalid, I will put the form inside the TR and use inputs. 
Actually form cannot be child of TBODY or TR directly.
I will make each ROW a form? HTML validation fails.
Correct way: One form per row wrapping the whole table? No.
Correct way: Form outside, or input `form="form-id"` attribute. 
I'll use `form="form-{{$control->id}}"` on the inputs and put the form hidden somewhere or just around the button?
Actually, simplest way is to wrap inputs in a form inside the TD if it's one TD? But here multiple TDs.
I'll rewrite the structure to be compliant. -->

@endsection
