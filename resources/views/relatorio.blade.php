<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Meu Histórico de Horas') }}
            </h2>

            <a href="{{ route('relatorio.export') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Exportar Planilha
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-6 flex justify-end">
                <a href="{{ route('relatorio.export') }}"
   style="background-color: #16a34a; color: white; padding: 10px 20px; border-radius: 6px; text-decoration: none; font-weight: bold; font-size: 14px; display: inline-flex; align-items: center;">
    <svg style="width: 16px; height: 16px; margin-right: 8px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
    </svg>
    Exportar Planilha
</a>
            </div>

            @foreach($registrosAgrupados as $ano => $meses)
                <h3 class="text-2xl font-bold text-gray-700 mb-6 mt-8">{{ $ano }}</h3>

                @foreach($meses as $mes => $registros)
    <details class="bg-white shadow-sm sm:rounded-lg mb-4 group" open>
        <summary class="flex items-center justify-between p-6 cursor-pointer list-none font-semibold text-indigo-600 uppercase hover:bg-gray-50 transition">
            <span>{{ $mes }}</span>
            <span class="transition group-open:rotate-180">
                <svg fill="none" height="24" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" width="24"><path d="M6 9l6 6 6-6"></path></svg>
            </span>
        </summary>

        <div class="px-6 pb-6 overflow-x-auto">
            <table class="w-full text-left border-collapse">
    <thead>
    <tr class="border-b bg-gray-50">
        <th class="py-3 px-4 text-sm font-bold uppercase text-gray-600">Datas</th>
        <th class="py-3 px-4 text-sm font-bold uppercase text-gray-600 text-center">Entrada</th>
        <th class="py-3 px-4 text-sm font-bold uppercase text-gray-600 text-center">Saída</th>
        <th class="py-3 px-4 text-sm font-bold uppercase text-gray-600 text-center">Total do Dia</th>
    </tr>
</thead>
    <tbody>
    @foreach($registros as $registro)
        <tr class="border-b hover:bg-gray-50 transition">
            <td class="py-3 px-4 text-sm">{{ \Carbon\Carbon::parse($registro->data)->format('d/m/Y') }}</td>
            <td class="py-3 px-4 text-sm text-center font-mono">{{ $registro->hora_inicio }}</td>
            <td class="py-3 px-4 text-sm text-center font-mono">{{ $registro->hora_saida }}</td>
            <td class="py-3 px-4 text-sm text-center font-bold text-indigo-700">{{ $registro->total_dia }}h</td>
        </tr>
    @endforeach
</tbody>
<tfoot>
    <tr class="bg-gray-100 font-bold">
        <td colspan="3" class="py-3 px-4 text-sm text-left uppercase text-gray-700">Total do Mês:</td>
        <td class="py-3 px-4 text-sm text-center text-indigo-900 border-l border-gray-200">
            {{ $registros->total_geral_mes }}h
        </td>
    </tr>
</tfoot>
</table>
        </div>
    </details>
@endforeach
            @endforeach

            @if($registrosAgrupados->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center text-gray-500">
                    Nenhum registro encontrado.
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
