<?php

namespace App\Http\Controllers;

use App\Models\Permohonan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    /**
     * Get raw expression for date part extraction based on database type
     */
    protected function rawDatePart($column, $part)
    {
        $dbType = Config::get('database.default');

        if ($dbType === 'pgsql') {
            return DB::raw("EXTRACT($part FROM $column) as $part");
        } else {
            return DB::raw("$part($column) as $part");
        }
    }

    /**
     * Get raw expression for date difference in days
     */
    /**
     * Get a database-agnostic date difference expression
     *
     * @param string $date1 The first date column
     * @param string $date2 The second date column
     * @param bool $forAggregate Whether this is for an aggregate function
     * @return \Illuminate\Database\Query\Expression
     */
    protected function rawDateDiff($date1, $date2, $forAggregate = false)
    {
        $connection = DB::connection();
        $driver = $connection->getDriverName();

        if ($driver === 'pgsql') {
            if ($forAggregate) {
                // For PostgreSQL, use simple date subtraction for days
                return $connection->raw("($date1::date - $date2::date)::float");
            }
            return $connection->raw("($date1 - $date2)");
        }

        // Default to MySQL/MariaDB syntax
        return $connection->raw("DATEDIFF($date1, $date2)");
    }

    /**
     * Get current date function based on database type
     */
    protected function rawCurrentDate()
    {
        $dbType = Config::get('database.default');

        if ($dbType === 'pgsql') {
            return 'CURRENT_DATE';
        } else {
            return 'CURDATE()';
        }
    }


    public function index()
    {
        $user = auth()->user();
        $company = $user->company;
        $now = Carbon::now();
        $startOfMonth = $now->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();

        if ($user->hasRole('supervisor') || $user->hasRole('inspektor')) {
            // Project stats
            $ongoing = \App\Models\Report::where('company_id', auth()->user()->company_id)
                ->where(function ($query) {
                    $query->whereNull('submit_date')
                        ->orWhereNull('supervisor_verifikasi')
                        ->orWhere('supervisor_verifikasi', 'revised');
                })
                ->count();

            $completed = \App\Models\Report::where('company_id', auth()->user()->company_id)
                ->where('supervisor_verifikasi', 'approved')
                ->whereBetween('supervisor_date', [$startOfMonth, $endOfMonth])
                ->count();

            $total = \App\Models\Report::where('company_id', auth()->user()->company_id)
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->count();

            // Get monthly report counts for past year for this company
            $yearlyStats = \App\Models\Report::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as total')
            )
                ->where('company_id', auth()->user()->company_id)
                ->where('created_at', '>=', Carbon::now()->subYear())
                ->groupBy('year', 'month')
                ->orderBy('year')
                ->orderBy('month')
                ->get();

            // Format data for chart
            $chartLabels = [];
            $chartData = [];
            foreach ($yearlyStats as $stat) {
                $chartLabels[] = Carbon::createFromDate($stat->year, $stat->month, 1)->format('M Y');
                $chartData[] = $stat->total;
            }

            // Get company performance stats
            $companyStats = \App\Models\Report::where('company_id', auth()->user()->company_id)
                ->whereNotNull('submit_date')
                ->whereNotNull('supervisor_date')
                ->select(
                    DB::raw('AVG(DATEDIFF(submit_date, date_of_measurement)) as avg_inspector_days'),
                    DB::raw('AVG(DATEDIFF(supervisor_date, submit_date)) as avg_supervisor_days')
                )
                ->get();

            // Job completion stats
            $inspektorReports = \App\Models\Report::where('company_id', auth()->user()->company_id)
                ->whereNotNull('submit_date')
                ->select(
                    DB::raw('AVG(DATEDIFF(submit_date, date_of_measurement)) as avg_days'),
                    DB::raw('COUNT(*) as total_reports')
                )
                ->first();

            $supervisorReviews = \App\Models\Report::where('company_id', auth()->user()->company_id)
                ->whereNotNull('supervisor_date')
                ->whereNotNull('submit_date')
                ->select(
                    DB::raw('AVG(DATEDIFF(supervisor_date, submit_date)) as avg_days'),
                    DB::raw('COUNT(*) as total_reviews')
                )
                ->first();

            $surveyorReviews = \App\Models\Report::where('company_id', auth()->user()->company_id)
                ->whereNotNull('surveyor_date')
                ->whereNotNull('submit_date')
                ->select(
                    DB::raw('AVG(DATEDIFF(surveyor_date, submit_date)) as avg_days'),
                    DB::raw('COUNT(*) as total_reviews')
                )
                ->first();

            // Top performers in company
            $topPerformers = \App\Models\Report::where('company_id', auth()->user()->company_id)
                ->whereNotNull('submit_date')
                ->whereBetween('submit_date', [$startOfMonth, $endOfMonth])
                ->select('user_id', DB::raw('count(*) as total_reports'))
                ->groupBy('user_id')
                ->with('user:id,name')
                ->orderBy('total_reports', 'desc')
                ->limit(5)
                ->get();

            return view('user/dashboard', compact('user', 'company', 'ongoing', 'completed', 'total', 'chartLabels', 'chartData', 'companyStats', 'topPerformers'));

        } else if ($user->hasRole('superadmin') || $user->hasRole('administrator')) {
            // Overall project stats
            // Get ongoing reports count
            $ongoing = \App\Models\Report::where(function ($query) {
                $query->whereNull('submit_date')
                    ->orWhereNull('supervisor_verifikasi')
                    ->orWhere('supervisor_verifikasi', 'revised');
            })
                ->count();

            // Get completed reports for current month
            $completed = \App\Models\Report::where('supervisor_verifikasi', 'approved')
                ->whereBetween('supervisor_date', [$startOfMonth, $endOfMonth])
                ->count();

            // Get total reports for current month
            $total = \App\Models\Report::whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->count();

            // Get monthly report counts for past year
            $yearlyStats = \App\Models\Report::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as total')
            )
                ->where('created_at', '>=', Carbon::now()->subYear())
                ->groupBy('year', 'month')
                ->orderBy('year')
                ->orderBy('month')
                ->get();

            // Format data for chart
            $chartLabels = [];
            $chartData = [];
            foreach ($yearlyStats as $stat) {
                $chartLabels[] = Carbon::createFromDate($stat->year, $stat->month, 1)->format('M Y');
                $chartData[] = $stat->total;
            }

            // Overall completion stats by company
            $companyStats = \App\Models\Report::select(
                'company_id',
                DB::raw('AVG(CASE WHEN submit_date IS NOT NULL THEN DATEDIFF(submit_date, date_of_measurement) END) as avg_inspector_days'),
                DB::raw('AVG(CASE WHEN supervisor_date IS NOT NULL THEN DATEDIFF(supervisor_date, submit_date) END) as avg_supervisor_days'),
                DB::raw('AVG(CASE WHEN surveyor_date IS NOT NULL THEN DATEDIFF(surveyor_date, supervisor_date) END) as avg_surveyor_days'),
                DB::raw('COUNT(*) as total_reports')
            )
                ->groupBy('company_id')
                ->with('company:id,name')
                ->get();

            // Top performing companies
            $topCompanies = \App\Models\Report::where('supervisor_verifikasi', 'approved')
                ->whereBetween('supervisor_date', [$startOfMonth, $endOfMonth])
                ->select('company_id', DB::raw('count(*) as total_reports'))
                ->groupBy('company_id')
                ->with('company:id,name')
                ->orderBy('total_reports', 'desc')
                ->limit(5)
                ->get();

            // Top performing inspectors/supervisors/surveyors
            $topUsers = \App\Models\Report::whereNotNull('submit_date')
                ->whereBetween('submit_date', [$startOfMonth, $endOfMonth])
                ->select('user_id', 'company_id', DB::raw('count(*) as total_reports'))
                ->groupBy('user_id', 'company_id')
                ->with(['user:id,name', 'company:id,name'])
                ->orderBy('total_reports', 'desc')
                ->limit(5)
                ->get();

            return view('user/dashboard', compact(
                'user',
                'ongoing',
                'completed',
                'total',
                'chartLabels',
                'chartData',
                'companyStats',
                'topCompanies',
                'topUsers'
            ));
        }

        return view('user/dashboard', compact('user', 'company'));
    }

    public function index_customer()
    {
        return view('administrator/customer');
    }

    public function index_tagihan()
    {
        $permohonans = Permohonan::all();
        return view('administrator/invoice', compact('permohonans'));
    }

    public function index_permohonan_barang_jasa()
    {
        $permohonans = Permohonan::where('tipe_permohonan', '=', 'BARANGJASA')->get();
        return view('user.permohonanbarangjasa', compact('permohonans'));
    }

    public function add_permohonan_barang_jasa(Request $request)
    {
        $post = "ADDPEMOHON";
        return view('user.permohonanbarangjasainput')->with('post', $post);
    }

    public function save_permohonan_barang_jasa(Request $request)
    {
        $pemohon = $request->input('pemohon');
        $alamat = $request->input('alamat');
        $paket_pengadaan = $request->input('paket_pengadaan');
        $keperluan = $request->input('keperluan');
        $no_dokumen = $request->input('no_dokumen');
        $direktur = $request->input('direktur');
        $cabang = strtoupper(Session::get('cabang'));
        $username = Session::get('cabang');
        $ynow = Carbon::now()->format('Y');
        $mnow = Carbon::now()->format('m');
        $namaentry = auth()->user()->name;
        //$nourut = Permohonan::select('no_urut')->where('cabang', $cabang)->orderBy('no_urut')->take(1)->get();
        $permohonandetails = DB::select("SELECT CASE WHEN no_urut is null then 0 else no_urut + 1 end as lastno FROM txpermohonan where cabang = '$cabang' ");

        foreach ($permohonandetails as $permohonandetail) {
            $lastno = $permohonandetail->lastno;
        }

        $kodecab = "";
        if ($cabang == 'BANTEN') {
            $kodecab = "CGC";
        }
        $no_permohonan = $lastno . '/' . $kodecab . '/' . $mnow . '-' . $ynow;
        //dd($no_permohonan);

        DB::insert("insert into txpermohonan (no_permohonan, no_urut, cabang, perusahaan, tipe_permohonan, alamat, paket_pengadaan, keperluan, no_dokumen, pic, verifikator, stat_verifikator, user_entry, tgl_entry) values ('$no_permohonan', '$lastno', '$cabang', '$pemohon', 'BARANGJASA', '$alamat', '$paket_pengadaan', '$keperluan', '$no_dokumen', '$direktur', '', '', '$namaentry', CURDATE())");

        $no_ids = DB::select("SELECT id, perusahaan, alamat, paket_pengadaan, keperluan, no_dokumen, pic FROM txpermohonan where no_permohonan = '$no_permohonan'");

        $post = "ADDITEM";
        return view('user.permohonanbarangjasainputdetail', compact('no_ids'))->with('post', $post);
    }

    public function index_permohonan_barang()
    {
        $permohonans = Permohonan::where('tipe_permohonan', '=', 'BARANG')->get();
        return view('user.permohonanbarang', compact('permohonans'));
    }

    public function index_permohonan_jasa()
    {
        $permohonans = Permohonan::where('tipe_permohonan', '=', 'JASA')->get();
        return view('user/permohonanjasa', compact('permohonans'));
    }

    public function index_permohonan()
    {
        return view('administrator/permohonan');
    }
}
