<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('worklog.store') }}" class="space-y-4">
    @csrf
    <div>
        <x-input-label for="hora_inicio" value="Hora de Entrada" />
        <x-text-input id="hora_inicio" name="hora_inicio" type="time" class="block mt-1 w-full" required />
    </div>

    <div>
        <x-input-label for="hora_saida" value="Hora de Saída" />
        <x-text-input id="hora_saida" name="hora_saida" type="time" class="block mt-1 w-full" required />
    </div>

    <x-primary-button>
        Calcular Horas
    </x-primary-button>
</form>

@if (session('status'))
    <div class="mt-4 p-4 bg-green-100 text-green-800 rounded">
        {{ session('status') }}
    </div>
@endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
