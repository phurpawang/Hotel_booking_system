<nav x-data="{ open: false }" style="background: linear-gradient(90deg, #1e293b 0%, #334155 100%); border-bottom: 3px solid #3b82f6; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center" style="display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-building" style="font-size: 1.5rem; background: linear-gradient(135deg, #60A5FA 0%, #A78BFA 50%, #F472B6 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;"></i>
                    <span style="font-size: 1.75rem; font-weight: 800; background: linear-gradient(135deg, #60A5FA 0%, #A78BFA 50%, #F472B6 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; letter-spacing: 0.5px; display: inline-block; text-shadow: 0 0 20px rgba(147, 197, 253, 0.3);">BHBS</span>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <form method="POST" action="{{ route('hotel.logout') }}">
                    @csrf
                    <button type="submit" style="background: linear-gradient(135deg, #DC2626 0%, #EF4444 100%); color: white; padding: 10px 24px; border-radius: 8px; font-weight: 600; box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3); display: inline-flex; align-items: center; transition: all 0.3s ease;" 
                            onmouseover="this.style.background='linear-gradient(135deg, #991B1B 0%, #DC2626 100%)'; this.style.boxShadow='0 6px 16px rgba(220, 38, 38, 0.4)'; this.style.transform='translateY(-2px)';" 
                            onmouseout="this.style.background='linear-gradient(135deg, #DC2626 0%, #EF4444 100%)'; this.style.boxShadow='0 4px 12px rgba(220, 38, 38, 0.3)'; this.style.transform='translateY(0)';">
                        <svg style="width: 20px; height: 20px; margin-right: 8px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        {{ __('Logout') }}
                    </button>
                </form>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" style="padding: 8px; border-radius: 6px; color: #e2e8f0; transition: all 0.15s ease-in-out;" onmouseover="this.style.backgroundColor='rgba(255,255,255,0.1)'; this.style.color='#ffffff';" onmouseout="this.style.backgroundColor='transparent'; this.style.color='#e2e8f0';">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1" style="border-top: 1px solid #475569;">
            <div class="px-4 py-2">
                <!-- Authentication -->
                <form method="POST" action="{{ route('hotel.logout') }}">
                    @csrf
                    <button type="submit" style="width: 100%; background: linear-gradient(135deg, #DC2626 0%, #EF4444 100%); color: white; padding: 12px 24px; border-radius: 8px; font-weight: 600; box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3); display: inline-flex; align-items: center; justify-content: center; transition: all 0.3s ease;" 
                            onmouseover="this.style.background='linear-gradient(135deg, #991B1B 0%, #DC2626 100%)'; this.style.boxShadow='0 6px 16px rgba(220, 38, 38, 0.4)';" 
                            onmouseout="this.style.background='linear-gradient(135deg, #DC2626 0%, #EF4444 100%)'; this.style.boxShadow='0 4px 12px rgba(220, 38, 38, 0.3)';">
                        <svg style="width: 20px; height: 20px; margin-right: 8px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        {{ __('Logout') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
