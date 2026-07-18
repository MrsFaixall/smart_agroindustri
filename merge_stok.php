<?php
$stoks = App\Models\Stok::all();
$merged = [];

foreach ($stoks as $stok) {
    $key = $stok->gudang_id . '_' . $stok->jenis_kentang_id . '_' . $stok->grade;
    if (!isset($merged[$key])) {
        $merged[$key] = $stok;
    } else {
        $merged[$key]->jumlah_stok += $stok->jumlah_stok;
        $merged[$key]->save();
        $stok->delete();
    }
}
echo "Stok merged successfully.\n";
