<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Registro;
use Illuminate\Support\Facades\Auth;

class HoraController extends Controller
{
    public function store(Request $request)
    {
        // Valida se os campos foram preenchidos
        $request->validate([
            'hora_inicio' => 'required',
            'hora_saida' => 'required',
        ]);

        // Transforma o texto das caixas em objetos de data/hora
        $inicio = Carbon::parse($request->hora_inicio);
        $saida = Carbon::parse($request->hora_saida);

        // Calcula a diferença amigável (ex: 08:30)
        $diferenca = $inicio->diff($saida)->format('%H:%I');

        // Salva no banco de dados
        // Importante: verifique se seu Model é WorkLog ou Registro
        \App\Models\Registro::create([
            'user_id' => auth()->id(),
            'hora_inicio' => $request->hora_inicio,
            'hora_saida' => $request->hora_saida,
            'data' => now()->format('Y-m-d'),
        ]);

        // Retorna para a dashboard com o resultado
        return back()->with('status', "Total trabalhado: $diferenca horas");
    }

public function index()
{
    $registrosAgrupados = \App\Models\Registro::where('user_id', auth()->id())
        ->orderBy('data', 'desc')
        ->get()
        ->groupBy([
            function ($val) { return \Carbon\Carbon::parse($val->data)->format('Y'); },
            function ($val) { return \Carbon\Carbon::parse($val->data)->translatedFormat('F'); }
        ]);

    foreach ($registrosAgrupados as $ano => $meses) {
        foreach ($meses as $mes => $registros) {
    $minutosMes = 0;
    foreach ($registros as $reg) {
        $inicio = \Carbon\Carbon::parse($reg->hora_inicio);
        $saida = \Carbon\Carbon::parse($reg->hora_saida);

        // Calculamos a diferença absoluta em minutos para evitar o sinal de menos
        $diffMinutos = abs($saida->diffInMinutes($inicio));

        $reg->total_dia = sprintf('%02d:%02d', floor($diffMinutos / 60), $diffMinutos % 60);
        $minutosMes += $diffMinutos;
    }
    $registros->total_geral_mes = sprintf('%02d:%02d', floor($minutosMes / 60), $minutosMes % 60);
}
    }

    return view('relatorio', compact('registrosAgrupados'));
}

public function export()
{
    $registros = \App\Models\Registro::where('user_id', auth()->id())->get();
    $fileName = 'relatorio_horas_' . now()->format('d_m_Y') . '.csv';

    $headers = [
        "Content-type"        => "text/csv; charset=UTF-8", // Definindo o padrão de texto
        "Content-Disposition" => "attachment; filename=$fileName",
        "Pragma"              => "no-cache",
        "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
        "Expires"             => "0"
    ];

    $callback = function() use($registros) {
        $file = fopen('php://output', 'w');

        // ESSA LINHA É O SEGREDO: Avisa ao Excel que o separador é o ponto e vírgula
        fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

        // Cabeçalhos usando ponto e vírgula
        fputcsv($file, ['Data', 'Entrada', 'Saida'], ';');

        foreach ($registros as $reg) {
            // Formata a data para o padrão BR no Excel e usa ponto e vírgula
            fputcsv($file, [
                \Carbon\Carbon::parse($reg->data)->format('d/m/Y'),
                $reg->hora_inicio,
                $reg->hora_saida
            ], ';');
        }
        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}

}
