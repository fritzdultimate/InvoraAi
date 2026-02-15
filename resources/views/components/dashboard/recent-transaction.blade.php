@props(['transactions'])

<div class="grid grid-cols-12 col-span-12">
    <div class="col-span-12">
        <div class="card border-0 overflow-hidden">
            <div class="card-header">
                <h6 class="card-title mb-0 text-lg">Transactions</h6>
            </div>
            <div class="card-body">
                <table id="selection-table"
                    class="border  border-neutral-200 dark:border-neutral-600 rounded-lg border-separate p-4">
                    <thead>
                        <tr>
                            <th scope="col" class="text-neutral-800 dark:text-white">
                                <div class="flex items-center gap-2">
                                    Reference
                                    <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                                    </svg>
                                </div>
                            </th>
                            <th scope="col" class="text-neutral-800 dark:text-white">
                                <div class="flex items-center gap-2">
                                    Type
                                    <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                                    </svg>
                                </div>
                            </th>
                            <th scope="col" class="text-neutral-800 dark:text-white">
                                <div class="flex items-center gap-2">
                                    Amount
                                    <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                                    </svg>
                                </div>
                            </th>
                            <th scope="col" class="text-neutral-800 dark:text-white">
                                <div class="flex items-center gap-2">
                                    Date
                                    <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                                    </svg>
                                </div>
                            </th>
                            <th scope="col" class="text-neutral-800 dark:text-white">
                                <div class="flex items-center gap-2">
                                    Action
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $txn)
                            <tr>
                                <td>
                                    <a href="javascript:void(0)" class="text-primary-600">
                                        #{{ $txn->reference ?? $txn->id }}
                                    </a>
                                </td>
                                <td> 
                                    <span class="bg-success-100 dark:bg-success-600/25 text-success-600 dark:text-success-400 px-6 py-1.5 rounded-full font-medium text-sm">
                                        {{ $txn->reference_type->value }}
                                    </span>
                                </td>
                                <td>
                                    @if($txn->type === 'credit')
                                        <span class="text-green-600 font-semibold">
                                            +${{ number_format($txn->credit, 2) }}
                                        </span>
                                    @else
                                        <span class="text-red-600 font-semibold">
                                            -${{ number_format($txn->debit, 2) }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    {{ $txn->created_at->format('M d Y') }}
                                </td>
                                <td>
                                    <a href="javascript:void(0)"
                                        class="w-8 h-8 bg-danger-100 dark:bg-danger-600/25 text-danger-600 dark:text-danger-400 rounded-full inline-flex items-center justify-center">
                                        <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-6 text-neutral-500">
                                    No transactions found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
    if (document.getElementById("selection-table") && typeof simpleDatatables.DataTable !== 'undefined') {

        let multiSelect = true;
        let rowNavigation = false;
        let table = null;

        const resetTable = function () {
            if (table) {
                table.destroy();
            }

            const options = {
                columns: [
                    { select: [0, 6], sortable: false } // Disable sorting on the first column (index 0 and 6)
                ],
                rowRender: (row, tr, _index) => {
                    if (!tr.attributes) {
                        tr.attributes = {};
                    }
                    if (!tr.attributes.class) {
                        tr.attributes.class = "";
                    }
                    if (row.selected) {
                        tr.attributes.class += " selected";
                    } else {
                        tr.attributes.class = tr.attributes.class.replace(" selected", "");
                    }
                    return tr;
                }
            };
            if (rowNavigation) {
                options.rowNavigation = true;
                options.tabIndex = 1;
            }

            table = new simpleDatatables.DataTable("#selection-table", options);

            // Mark all rows as unselected
            table.data.data.forEach(data => {
                data.selected = false;
            });

            table.on("datatable.selectrow", (rowIndex, event) => {
                event.preventDefault();
                const row = table.data.data[rowIndex];
                if (row.selected) {
                    row.selected = false;
                } else {
                    if (!multiSelect) {
                        table.data.data.forEach(data => {
                            data.selected = false;
                        });
                    }
                    row.selected = true;
                }
                table.update();
            });
        };

        // Row navigation makes no sense on mobile, so we deactivate it and hide the checkbox.
        const isMobile = window.matchMedia("(any-pointer:coarse)").matches;
        if (isMobile) {
            rowNavigation = false;
        }

        resetTable();
    }
    </script>
@endpush