<?php

namespace App\Controllers;

use App\Models\DownloadModel;

/**
 * Downloads Controller
 *
 *   GET /downloads              → index (list categories / all downloads)
 *   GET /downloads/(:segment)  → category (e.g. books, software)
 *   GET /download/(:num)        → view (single download page)
 *   GET /download/file/(:num)   → file (actual file download – requires login)
 *
 * The route for download/file/(:num) is protected by the 'auth' filter,
 * so only logged-in members can download. Others are redirected to /login.
 */
class Downloads extends BaseController
{
    public function index()
    {
        $categories = [];
        $downloads  = [];
        try {
            $db    = \Config\Database::connect();
            $model = model(DownloadModel::class);
            $categoriesQuery = $db->table('download_categories')->orderBy('name', 'ASC')->get();
            $categories      = ($categoriesQuery !== false) ? $categoriesQuery->getResultArray() : [];
            $downloads       = $model->getActive();
        } catch (\Throwable $e) {
            $categories = [];
            $downloads  = [];
        }

        $data = [
            'title'       => 'Downloads',
            'description' => 'Books, software, and resources',
            'categories'  => $categories,
            'downloads'   => $downloads,
        ];
        return view('downloads/index', $data);
    }

    public function category(string $slug)
    {
        try {
            $db         = \Config\Database::connect();
            $model      = model(DownloadModel::class);
            $catQuery   = $db->table('download_categories')->where('slug', $slug)->get();
            $cat        = ($catQuery !== false) ? $catQuery->getRowArray() : null;
            if (! $cat) {
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            }
            $downloads  = $model->getByCategory((int) $cat['id']);
        } catch (\CodeIgniter\Exceptions\PageNotFoundException $e) {
            throw $e;
        } catch (\Throwable $e) {
            return redirect()->to(base_url('downloads'))->with('error', 'Categories temporarily unavailable. Please try again.');
        }

        $data = [
            'title'       => 'Downloads - ' . ($cat['name'] ?? $slug),
            'description' => 'Downloads in this category',
            'category'    => $cat,
            'downloads'   => $downloads,
        ];
        return view('downloads/category', $data);
    }

    public function view(int $id)
    {
        try {
            $model = model(DownloadModel::class);
            $item  = $model->find($id);
        } catch (\Throwable $e) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        if (! $item || empty($item['is_active'])) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $category = null;
        try {
            if (! empty($item['category_id'])) {
                $catQuery = \Config\Database::connect()->table('download_categories')->where('id', (int) $item['category_id'])->get();
                $category = ($catQuery !== false) ? $catQuery->getRowArray() : null;
            }
        } catch (\Throwable $e) {
            $category = null;
        }

        $data = [
            'title'    => $item['title'] ?? 'Download',
            'download' => $item,
            'category' => $category,
        ];
        return view('downloads/view', $data);
    }

    /**
     * Serve the actual file. This route MUST be protected by the 'auth' filter
     * so only logged-in users can reach it (see Routes.php).
     */
    public function file(int $id)
    {
        try {
            $model = model(DownloadModel::class);
            $item  = $model->find($id);
        } catch (\Throwable $e) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        if (! $item || empty($item['is_active']) || empty($item['file_path'])) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $path = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . $item['file_path'];
        $path = realpath($path);
        if ($path === false || ! is_file($path)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        try {
            $db = \Config\Database::connect();
            $db->table('download_logs')->insert([
                'user_id'       => session()->get('user_id'),
                'download_id'   => $id,
                'downloaded_at' => date('Y-m-d H:i:s'),
            ]);
        } catch (\Throwable $e) {
            // Log optional; continue to serve file
        }

        return $this->response->download($path, null, true);
    }
}
