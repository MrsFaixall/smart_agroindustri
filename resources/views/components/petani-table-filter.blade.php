@props([
    'searchRoute' => url()->current(),
    'placeholder' => 'Cari data...',
    'showDate' => true
])

<div class="bg-white p-4 rounded-3xl shadow-xl shadow-slate-100/60 border border-slate-100 mb-6" x-data="tableFilter()">
    <div class="flex flex-col lg:flex-row items-stretch lg:items-center gap-3 w-full">
        <!-- Text Search -->
        <div class="relative flex-1 min-w-[200px]">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <input type="text" 
                x-model="searchQuery" 
                @input="applyFilter"
                class="block w-full pl-11 pr-4 py-3 border border-slate-200 rounded-2xl text-sm bg-slate-50/50 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all font-medium" 
                placeholder="{{ $placeholder }}">
        </div>

        @if($showDate)
        <!-- Date Search -->
        <div class="relative w-full lg:w-[220px]">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <input type="date" 
                x-model="dateQuery"
                @input="applyFilter"
                class="block w-full pl-11 pr-4 py-3 border border-slate-200 rounded-2xl text-sm bg-slate-50/50 text-slate-600 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all font-medium">
        </div>
        @endif
        
        <button type="button" @click="resetFilter" x-show="searchQuery || dateQuery" style="display: none;" class="px-5 py-3 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm font-bold transition-colors">
            Reset
        </button>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('tableFilter', () => ({
        searchQuery: '',
        dateQuery: '',
        
        applyFilter() {
            const query = this.searchQuery.toLowerCase();
            const date = this.dateQuery;
            
            const container = this.$el.closest('.space-y-8') || document.body;
            const rows = container.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                if(row.querySelector('td[colspan]')) return;
                
                const text = row.textContent.toLowerCase();
                const matchesSearch = query === '' || text.includes(query);
                
                let matchesDate = true;
                if(date) {
                    const d = new Date(date);
                    if(!isNaN(d.getTime())) {
                        const day = String(d.getDate()).padStart(2, '0');
                        const dayNoZero = d.getDate();
                        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'];
                        const month = months[d.getMonth()];
                        const year = d.getFullYear();
                        
                        const format1 = `${day} ${month} ${year}`.toLowerCase();
                        const format2 = `${day}/${String(d.getMonth()+1).padStart(2, '0')}/${year}`;
                        const format3 = `${dayNoZero} ${month} ${year}`.toLowerCase();
                        
                        matchesDate = text.includes(format1) || text.includes(format2) || text.includes(format3) || text.includes(date);
                    }
                }
                
                if(matchesSearch && matchesDate) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        },
        
        resetFilter() {
            this.searchQuery = '';
            this.dateQuery = '';
            this.applyFilter();
        }
    }));
});
</script>
