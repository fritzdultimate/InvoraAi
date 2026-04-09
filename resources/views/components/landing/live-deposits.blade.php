
@push('styles')
    <style>
    /* ================= LIVE DEPOSITS – PREMIUM THEME ================= */

    .live-deposits-wrapper {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 18px;
    }

    /* Card */
    .deposit-row {
        position: relative;
        padding: 18px 20px;
        border-radius: 16px;
        background: linear-gradient(180deg, #0f172a, #020617);
        border: 1px solid rgba(255,255,255,0.06);
        display: flex;
        flex-direction: column;
        gap: 12px;
        animation: fadeSlide 0.6s ease;
    }

    /* subtle glow */
    .deposit-row::before {
        content: "";
        position: absolute;
        inset: 0;
        border-radius: 16px;
        padding: 1px;
        background: linear-gradient(135deg, #22c55e, #3b82f6);
        -webkit-mask:
            linear-gradient(#000 0 0) content-box,
            linear-gradient(#000 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
        opacity: 0.35;
    }

    /* Top row */
    .deposit-user {
        font-size: 13px;
        font-weight: 600;
        color: #94a3b8;
    }

    /* Amount highlight */
    .deposit-amount {
        font-size: 22px;
        font-weight: 700;
        color: #22c55e;
        letter-spacing: 0.3px;
    }

    /* Footer */
    .deposit-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 12px;
        margin-top: 4px;
    }

    .deposit-time {
        color: #64748b;
    }

    .deposit-hash a {
        color: #60a5fa;
        font-weight: 500;
        text-decoration: none;
    }

    .deposit-hash a:hover {
        text-decoration: underline;
    }

    /* ================= MOBILE ================= */
    @media (max-width: 768px) {
        .live-deposits-wrapper {
            grid-template-columns: 1fr;
        }

        .deposit-amount {
            font-size: 20px;
        }
    }

    /* Animation */
    @keyframes fadeSlide {
        from {
            opacity: 0;
            transform: translateY(12px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    </style>
@endpush




{{-- Live deposits --}}

<div data-elementor-type="wp-page" data-elementor-id="295" class="elementor elementor-295"
    data-elementor-post-type="page">
    <div class="elementor-element elementor-element-5c24357 e-flex e-con-boxed e-con e-parent" data-id="5c24357"
        data-element_type="container" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
        <div class="e-con-inner">
            <div class="elementor-element elementor-element-0565c22 e-flex e-con-boxed e-con e-child" data-id="0565c22"
                data-element_type="container">
                <div class="e-con-inner">
                    <div class="elementor-element elementor-element-b1fc91f elementor-invisible elementor-widget elementor-widget-heading"
                        data-id="b1fc91f" data-element_type="widget"
                        data-settings="{&quot;_animation_delay&quot;:200,&quot;_animation&quot;:&quot;fadeInUp&quot;}"
                        data-widget_type="heading.default">
                        <div class="elementor-widget-container">
                            <h6 class="elementor-heading-title elementor-size-default">Latest Deposits</h6>
                        </div>
                    </div>
                    <div class="elementor-element elementor-element-f8d3e0b elementor-invisible elementor-widget elementor-widget-heading"
                        data-id="f8d3e0b" data-element_type="widget"
                        data-settings="{&quot;_animation&quot;:&quot;fadeInUp&quot;,&quot;_animation_delay&quot;:300}"
                        data-widget_type="heading.default">
                        <div class="elementor-widget-container">
                            <h1 class="elementor-heading-title elementor-size-default">
                                Real-time feed of recent deposits funding AI-powered arbitrage trades
                            </h1>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Table / Cards --}}
            <div class="live-deposits-wrapper elementor-element elementor-element-ffec643 e-con-full e-grid e-con e-child gap-5! md:grid-cols-3!" id="liveDeposits"></div>


        </div>
    </div>
</div>

@script()
<script>
    const deposits = [
        { user: "0xA3F...92D", amount: 250, hash: "0x7e3a9b5c4f8d0e9a" },
        { user: "0xF91...A2C", amount: 1200, hash: "0x4c8f12a7e9b3d6fa" },
        { user: "0x92D...E81", amount: 500, hash: "0xb91a2f7e6d3c8a4f" },
        { user: "0x71C...B90", amount: 3000, hash: "0x3e8d6f1a4b9c2d7e" },
        { user: "0xD21...44F", amount: 800, hash: "0x9f1b8a6c4e7d3a2f" },
    ];

    const container = document.getElementById('liveDeposits');

    function timeAgo() {
        const mins = Math.floor(Math.random() * 59) + 1;
        return `${mins} min ago`;
    }

    function renderDeposit(d) {
        const row = document.createElement('div');
        row.className = 'deposit-row';

        row.innerHTML = `
            <div class="deposit-user">${d.user}</div>

            <div class="deposit-amount">
                $${d.amount.toLocaleString()}
            </div>

            <div class="deposit-meta">
                <div class="deposit-time">${timeAgo()}</div>
                <div class="deposit-hash">
                    <a href="https://etherscan.io/tx/${d.hash}" target="_blank">
                        View Tx →
                    </a>
                </div>
            </div>
        `;

        container.prepend(row);

        if (container.children.length > 6) {
            container.removeChild(container.lastChild);
        }
    }

    let index = 0;
    setInterval(() => {
        renderDeposit(deposits[index]);
        index = (index + 1) % deposits.length;
    }, 3000);
</script>
@endscript