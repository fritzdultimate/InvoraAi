<x-filament-widgets::widget>
    <x-filament::section>

    <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">

        <button
            wire:click="setBull"
            style="padding:8px 12px; background:#22c55e; color:white; border-radius:8px;"
        >
            <span wire:loading.remove wire:target="setBull">
                Bull 🚀
            </span>

            <span wire:loading wire:target="setBull" class="btn-loader">
                <span class="spinner"></span>
                Processing...
            </span>
        </button>

        <button
            wire:click="setNeutral"
            style="padding:8px 12px; background:#3b82f6; color:white; border-radius:8px;"
        >
             <span wire:loading.remove wire:target="setNeutral">
                Neutral 😐
            </span>

            <span wire:loading wire:target="setNeutral" class="btn-loader">
                <span class="spinner"></span>
                Processing...
            </span>
            
        </button>

        <button
            wire:click="setBear"
            style="padding:8px 12px; background:#ef4444; color:white; border-radius:8px;"
        >
            <span wire:loading.remove wire:target="setBear">
                Bear 📉
            </span>

            <span wire:loading wire:target="setBear" class="btn-loader">
                <span class="spinner"></span>
                Processing...
            </span>
            
        </button>

    </div>

</x-filament::section>
</x-filament-widgets::widget>
