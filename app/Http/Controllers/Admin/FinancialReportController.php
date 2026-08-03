<?php

namespace App\Http\Controllers\Admin;

use App\Exports\FinancialReportExport;
use App\Http\Controllers\Controller;
use App\Models\Infaq;
use App\Models\Zakat;
use App\Models\QurbanOrder;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FinancialReportController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $this->getFilters($request);

        $infaqs = $this->filteredInfaqsQuery($filters)
            ->with(['user', 'category', 'campaign'])
            ->latest('paid_at')
            ->paginate(15, ['*'], 'infaqs_page')
            ->withQueryString();

        $zakats = $this->filteredZakatsQuery($filters)
            ->with(['user', 'zakatType'])
            ->latest('paid_at')
            ->paginate(15, ['*'], 'zakats_page')
            ->withQueryString();

        $qurbanOrders = $this->filteredQurbanOrdersQuery($filters)
            ->with(['animal'])
            ->latest('paid_at')
            ->paginate(15, ['*'], 'qurban_page')
            ->withQueryString();

        $summary = [
            'total_infaq' => (clone $this->filteredInfaqsQuery($filters))
                ->where('payment_status', 'success')
                ->sum('amount'),
            'total_zakat' => (clone $this->filteredZakatsQuery($filters))
                ->where('payment_status', 'success')
                ->sum('amount'),
            'total_qurban' => (clone $this->filteredQurbanOrdersQuery($filters))
                ->where('payment_status', 'success')
                ->sum('total_amount'),
            'qurban_by_type' => (clone $this->filteredQurbanOrdersQuery($filters))
                ->join('sacrificial_animals', 'qurban_orders.sacrificial_animal_id', '=', 'sacrificial_animals.id')
                ->selectRaw('sacrificial_animals.animal_type, COUNT(*) as total_orders, SUM(qurban_orders.total_amount) as total_amount')
                ->where('qurban_orders.payment_status', 'success')
                ->groupBy('sacrificial_animals.animal_type')
                ->get(),
        ];

        return view('admin.reports.index', compact('infaqs', 'zakats', 'qurbanOrders', 'summary', 'filters'));
    }

    public function exportExcel(Request $request): BinaryFileResponse
    {
        $filters = $this->getFilters($request);
        $fileName = 'laporan-keuangan-' . now()->format('Y-m-d') . '.xlsx';

        return Excel::download(new FinancialReportExport($filters), $fileName);
    }

    public function exportPdf(Request $request): Response
    {
        $filters = $this->getFilters($request);

        $infaqs = $this->filteredInfaqsQuery($filters)
            ->with(['user', 'category', 'campaign'])
            ->latest('paid_at')
            ->get();

        $zakats = $this->filteredZakatsQuery($filters)
            ->with(['user', 'zakatType'])
            ->latest('paid_at')
            ->get();

        $qurbanOrders = $this->filteredQurbanOrdersQuery($filters)
            ->with(['animal'])
            ->latest('paid_at')
            ->get();

        $summary = [
            'total_infaq' => $infaqs->where('payment_status', 'success')->sum('amount'),
            'total_zakat' => $zakats->where('payment_status', 'success')->sum('amount'),
            'total_qurban' => $qurbanOrders->where('payment_status', 'success')->sum('total_amount'),
            'qurban_by_type' => (clone $this->filteredQurbanOrdersQuery($filters))
                ->join('sacrificial_animals', 'qurban_orders.sacrificial_animal_id', '=', 'sacrificial_animals.id')
                ->selectRaw('sacrificial_animals.animal_type, COUNT(*) as total_orders, SUM(qurban_orders.total_amount) as total_amount')
                ->where('qurban_orders.payment_status', 'success')
                ->groupBy('sacrificial_animals.animal_type')
                ->get(),
        ];

        $pdf = Pdf::loadView('pdf.financial-report', compact('infaqs', 'zakats', 'qurbanOrders', 'summary', 'filters'));

        return $pdf->stream('laporan-keuangan-' . now()->format('Y-m-d') . '.pdf');
    }

    public function exportCsv(Request $request): BinaryFileResponse
    {
        $filters = $this->getFilters($request);
        $fileName = 'laporan-keuangan-' . now()->format('Y-m-d') . '.csv';

        return Excel::download(new FinancialReportExport($filters), $fileName, \Maatwebsite\Excel\Excel::CSV);
    }

    private function getFilters(Request $request): array
    {
        return [
            'status' => $request->input('status'),
            'date_from' => $request->input('date_from'),
            'date_to' => $request->input('date_to'),
        ];
    }

    private function filteredInfaqsQuery(array $filters)
    {
        return Infaq::query()
            ->when(!empty($filters['status']), function ($query) use ($filters) {
                return $query->where('payment_status', $filters['status']);
            })
            ->when(!empty($filters['date_from']), function ($query) use ($filters) {
                return $query->whereDate('paid_at', '>=', $filters['date_from']);
            })
            ->when(!empty($filters['date_to']), function ($query) use ($filters) {
                return $query->whereDate('paid_at', '<=', $filters['date_to']);
            });
    }

    private function filteredZakatsQuery(array $filters)
    {
        return Zakat::query()
            ->when(!empty($filters['status']), function ($query) use ($filters) {
                return $query->where('payment_status', $filters['status']);
            })
            ->when(!empty($filters['date_from']), function ($query) use ($filters) {
                return $query->whereDate('paid_at', '>=', $filters['date_from']);
            })
            ->when(!empty($filters['date_to']), function ($query) use ($filters) {
                return $query->whereDate('paid_at', '<=', $filters['date_to']);
            });
    }

    private function filteredQurbanOrdersQuery(array $filters)
    {
        return QurbanOrder::query()
            ->when(!empty($filters['status']), function ($query) use ($filters) {
                return $query->where('payment_status', $filters['status']);
            })
            ->when(!empty($filters['date_from']), function ($query) use ($filters) {
                return $query->whereDate('paid_at', '>=', $filters['date_from']);
            })
            ->when(!empty($filters['date_to']), function ($query) use ($filters) {
                return $query->whereDate('paid_at', '<=', $filters['date_to']);
            });
    }
}
