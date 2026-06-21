<?php

namespace App\Http\Controllers;

use App\Models\HomeBanner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PanitiaBannerController extends Controller
{
    public function index(): View
    {
        return view('dashboard.panitia.banners.index', [
            'banners' => HomeBanner::query()->get()->keyBy('slot'),
        ]);
    }

    public function update(Request $request, int $slot): RedirectResponse
    {
        abort_unless(in_array($slot, [1, 2, 3], true), 404);

        $validated = $request->validate([
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ], [
            'image.required' => 'Pilih gambar banner terlebih dahulu.',
            'image.image' => 'File banner harus berupa gambar.',
            'image.mimes' => 'Format banner harus JPG, PNG, atau WEBP.',
            'image.max' => 'Ukuran banner maksimal 5 MB.',
        ]);

        $banner = HomeBanner::query()->where('slot', $slot)->first();
        $newPath = $validated['image']->store('home-banners', 'public');
        $oldPath = $banner?->image_path;

        HomeBanner::query()->updateOrCreate(
            ['slot' => $slot],
            ['image_path' => $newPath],
        );

        if ($oldPath) {
            Storage::disk('public')->delete($oldPath);
        }

        return back()->with('status', "Banner {$slot} berhasil diperbarui.");
    }

    public function destroy(int $slot): RedirectResponse
    {
        abort_unless(in_array($slot, [1, 2, 3], true), 404);

        $banner = HomeBanner::query()->where('slot', $slot)->first();

        if ($banner) {
            Storage::disk('public')->delete($banner->image_path);
            $banner->delete();
        }

        return back()->with('status', "Banner {$slot} berhasil dihapus. Gambar bawaan akan ditampilkan.");
    }
}
