@props(['paginator', 'label' => 'data'])

@if (isset($paginator) && $paginator->hasPages())
    <div class="mt-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
        <p class="text-xs text-slate-500 font-medium">
            Menampilkan <span class="font-bold text-slate-800">{{ $paginator->firstItem() ?? 0 }}</span> sampai <span class="font-bold text-slate-800">{{ $paginator->lastItem() ?? 0 }}</span> dari <span class="font-bold text-slate-800">{{ $paginator->total() }}</span> {{ $label }}
        </p>
        <div class="pagination-custom">
            {{ $paginator->links() }}
        </div>
    </div>
@endif
