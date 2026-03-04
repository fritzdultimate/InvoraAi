<aside class="sidebar">
	<button type="button" class="sidebar-close-btn !mt-4">
		<iconify-icon icon="radix-icons:cross-2"></iconify-icon>
	</button>
	<div class="">
		<a href="{{ route('dashboard') }}" class="sidebar-logo">
			<!-- <img src="{{ asset('assets/images/logo.png') }}" alt="site logo" class="light-logo"> -->
			<img src="{{ asset('logo/invora-logo-1.png') }}" alt="site logo" class="dark-logo">
			<!-- <img src="{{ asset('assets/images/logo-icon.png') }}" alt="site logo" class="logo-icon"> -->
			
		</a>
	</div>
	<div class="sidebar-menu-area">
		<ul class="sidebar-menu" id="sidebar-menu">
			<!-- <li class="dropdown">
				<a href="javascript:void(0)">
					<iconify-icon icon="solar:home-smile-angle-outline" class="menu-icon"></iconify-icon>
					<span>Dashboard</span>
				</a>
				<ul class="sidebar-submenu">
					<li>
						<a href="{{ route('dashboard') }}">
							<i class="ri-circle-fill circle-icon text-success-600 w-auto"></i> 
							Overview
						</a>
					</li>
				</ul>
			</li> -->
			<li>
				<a href="{{ route('dashboard') }}">
					<iconify-icon icon="solar:widget-5-bold" class="menu-icon"></iconify-icon>
					<span>Overview</span>
				</a>
			</li>

			<li class="sidebar-menu-group-title">Application</li>
			<li>
				<a href="{{ route('deposit') }}">
					<iconify-icon icon="solar:card-send-bold" class="menu-icon"></iconify-icon>
					<span>Deposit</span>
				</a>
			</li>

			<li>
				<a href="{{ route('withdrawal') }}">
					<iconify-icon icon="ph:coins-bold" class="menu-icon"></iconify-icon>
					<span>Withdrawal</span>
				</a>
			</li>

			<li class="sidebar-menu-group-title">License & Investment</li>

			<li>
				<a href="{{ route('bot') }}">
					<iconify-icon icon="mdi:robot-industrial-outline" class="menu-icon"></iconify-icon>
					<span>Bots/License</span>
				</a>
			</li>

			<li>
				<a href="{{ route('investments.create') }}">
					<iconify-icon icon="mdi:chart-line" class="menu-icon"></iconify-icon>
					<span>Investments</span>
				</a>
			</li>

			<li class="sidebar-menu-group-title">Referral Center</li>
			<li>
				<a href="{{ route('dashboard.referrals') }}">
					<iconify-icon icon="mdi:badge-account-outline" class="menu-icon"></iconify-icon>
					<span>Overview</span>
				</a>
			</li>

			<li>
				<a href="{{ route('dashboard.bonuses') }}">
					<iconify-icon icon="mdi:badge-account-outline" class="menu-icon"></iconify-icon>
					<span>Bonus</span>
				</a>
			</li>

			<li>
				<a href="{{ route('dashboard.referrals.direct') }}">
					<iconify-icon icon="mdi:badge-account-outline" class="menu-icon"></iconify-icon>
					<span>Direct Referrals</span>
				</a>
			</li>

			<li>
				<a href="{{ route('dashboard.referrals.network') }}">
					<iconify-icon icon="mdi:badge-account-outline" class="menu-icon"></iconify-icon>
					<span>My Network</span>
				</a>
			</li>

			<li>
				<a href="{{ route('dashboard.referrals.tree') }}">
					<iconify-icon icon="mdi:badge-account-outline" class="menu-icon"></iconify-icon>
					<span>Tree</span>
				</a>
			</li>

			<li class="sidebar-menu-group-title">Account</li>
			<li>
				<a href="{{ route('profile') }}">
					<iconify-icon icon="mdi:badge-account-outline" class="menu-icon"></iconify-icon>
					<span>Profile</span>
				</a>
			</li>

			<li>
				<a href="#">
					<iconify-icon icon="mdi:shield-account-outline" class="menu-icon"></iconify-icon>
					<span>KYC</span>
				</a>
			</li>

			<li>
				<a href="#">
					<iconify-icon icon="mdi:ticket-confirmation-outline" class="menu-icon"></iconify-icon>
					<span>Tickets</span>
				</a>
			</li>
		</ul>
	</div>
</aside>