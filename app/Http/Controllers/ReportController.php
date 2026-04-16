<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReportRequest;
use App\Jobs\GenerateReportJob;
use App\Models\ReportRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function store(StoreReportRequest $request): JsonResponse
    {
        try {
            $report = ReportRequest::create([
                'user_id' => $request->user()->id,
                'type' => $request->validated('type'),
                'filters' => $request->validated('filters'),
                'status' => 'pending',
            ]);

            GenerateReportJob::dispatch($report->id)
                ->onConnection('rabbitmq')
                ->onQueue('reports');

            return response()->json([
                'data' => [
                    'id' => $report->id,
                    'type' => $report->type,
                    'status' => $report->status,
                ],
                'message' => 'Relatório sendo gerado. Você receberá um e-mail quando estiver pronto.',
            ], 202);
        } catch (\Exception $e) {
            Log::error($e->getMessage().' in '.$e->getFile().':'.$e->getLine());

            return response()->json(['message' => 'Ocorreu um erro interno. Tente novamente.'], 500);
        }
    }

    public function show(Request $request, int $id): JsonResponse
    {
        try {
            $report = ReportRequest::where('id', $id)
                ->where('user_id', $request->user()->id)
                ->first();

            if (! $report) {
                return response()->json(['message' => 'Relatório não encontrado.'], 404);
            }

            return response()->json([
                'data' => [
                    'id' => $report->id,
                    'type' => $report->type,
                    'status' => $report->status,
                    'filters' => $report->filters,
                    'created_at' => $report->created_at->toDateTimeString(),
                ],
            ], 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage().' in '.$e->getFile().':'.$e->getLine());

            return response()->json(['message' => 'Ocorreu um erro interno. Tente novamente.'], 500);
        }
    }

    public function download(Request $request, int $id): StreamedResponse|JsonResponse
    {
        try {
            $report = ReportRequest::where('id', $id)
                ->where('user_id', $request->user()->id)
                ->first();

            if (! $report) {
                return response()->json(['message' => 'Relatório não encontrado.'], 404);
            }

            if ($report->status !== 'done' || ! $report->file_path) {
                return response()->json([
                    'message' => 'Relatório ainda não está disponível.',
                    'status' => $report->status,
                ], 422);
            }

            $mimeType = $report->type === 'pdf' ? 'application/pdf' : 'text/csv';
            $filename = "relatorio-{$report->id}.{$report->type}";

            return Storage::download($report->file_path, $filename, [
                'Content-Type' => $mimeType,
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage().' in '.$e->getFile().':'.$e->getLine());

            return response()->json(['message' => 'Ocorreu um erro interno. Tente novamente.'], 500);
        }
    }
}
