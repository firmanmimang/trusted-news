<aside
  x-cloak
  :class="sidebarToggle ? 'translate-x-0' : '-translate-x-full'"
  class="absolute left-0 top-0 z-9999 flex h-screen w-72.5 flex-col overflow-y-hidden bg-white duration-200 ease-in-out dark:bg-boxdark lg:static lg:translate-x-0"
  @click.outside="sidebarToggle = false"
>
  {{-- SIDEBAR HEADER --}}
  <div class="flex items-center justify-between gap-2 px-6 py-5.5 lg:py-6.5">
    <a href="/">
      <h1 class="text-2xl font-semibold whitespace-pre-wrap">CMS &vert; <span class="whitespace-nowrap">Trusted News</span></h1>
    </a>

    <button
      class="block lg:hidden"
      @click.stop="sidebarToggle = !sidebarToggle"
    >
      <svg class="fill-current" width="20" height="18" viewBox="0 0 20 18" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path
          d="M19 8.175H2.98748L9.36248 1.6875C9.69998 1.35 9.69998 0.825 9.36248 0.4875C9.02498 0.15 8.49998 0.15 8.16248 0.4875L0.399976 8.3625C0.0624756 8.7 0.0624756 9.225 0.399976 9.5625L8.16248 17.4375C8.31248 17.5875 8.53748 17.7 8.76248 17.7C8.98748 17.7 9.17498 17.625 9.36248 17.475C9.69998 17.1375 9.69998 16.6125 9.36248 16.275L3.02498 9.8625H19C19.45 9.8625 19.825 9.4875 19.825 9.0375C19.825 8.55 19.45 8.175 19 8.175Z"
          fill=""
        />
      </svg>
    </button>
  </div>
  {{-- SIDEBAR HEADER --}}

  <div class="flex flex-col overflow-y-auto duration-300 ease-linear no-scrollbar">
    {{-- Sidebar Menu --}}
    <nav
      class="px-4 py-4 mt-5 lg:mt-2 lg:px-6"
      x-data="{selected: $persist('Dashboard')}"
    >
      {{-- Menu Group --}}
      <div>
        <ul class="mb-6 flex flex-col gap-1.5">
          {{-- Menu Item Dashboard --}}
          <li>
            <a href="{{route('cms.dashboard')}}" class="group relative flex items-center gap-2.5 rounded-sm py-2 px-4 font-medium text-bodydark1 duration-300 ease-in-out hover:bg-graydark dark:hover:bg-meta-4">
              <x-icons.dashboard-icon width="18" height="18" />
              Dashboard
            </a>
          </li>
          {{-- <!-- Menu Item Dashboard --> --}}

          {{-- POSTS MENU --}}
          <li>
            <a
              href="#"
              @click.prevent="selected = (selected === 'Forms' ? '':'Forms')"
              :class="{ 'bg-graydark dark:bg-meta-4': (selected === 'Forms') || (page === 'formElements' || page === 'formLayout') }"
              class="group relative flex items-center gap-2.5 rounded-sm py-2 px-4 font-medium text-bodydark1 duration-300 ease-in-out hover:bg-graydark dark:hover:bg-meta-4"
            >
              <x-icons.dashboard-icon width="18" height="18" />
              News Section

              <x-icons.chevron-down-icon
                class="absolute transition duration-200 -translate-y-1/2 fill-current right-4 top-1/2"
                x-bind:class="{ 'rotate-180': (selected === 'Forms') }"
                width="20" height="20"
              />
            </a>

            <!-- Dropdown Menu Start -->
            <div :class="(selected === 'Forms') ? 'block' :'hidden'" class="overflow-hidden">
              <ul class="mt-4 mb-5.5 flex flex-col gap-2.5 pl-6">
                <li>
                  <a
                    class="group relative flex items-center gap-2.5 rounded-md px-4 font-medium text-bodydark2 duration-300 ease-in-out hover:text-white"
                    href="{{route('cms.category.index')}}"
                    :class="page === 'formElements' && '!text-white'"
                  >
                    Category
                  </a>
                </li>
                <li>
                  <a
                    class="group relative flex items-center gap-2.5 rounded-md px-4 font-medium text-bodydark2 duration-300 ease-in-out hover:text-white"
                    href="{{route('cms.news.in-house.index')}}"
                    :class="page === 'formLayout' && '!text-white'"
                  >
                    News In House
                  </a>
                </li>
                <li>
                  <a
                    class="group relative flex items-center gap-2.5 rounded-md px-4 font-medium text-bodydark2 duration-300 ease-in-out hover:text-white"
                    href="{{route('cms.news.crawl.index')}}"
                    :class="page === 'formLayout' && '!text-white'"
                  >
                    News Crawl
                  </a>
                </li>
              </ul>
            </div>
            <!-- Dropdown Menu End -->
          </li>

          <li>
            <a href="{{route('cms.classification.index')}}" class="group relative flex items-center gap-2.5 rounded-sm py-2 px-4 font-medium text-bodydark1 duration-300 ease-in-out hover:bg-graydark dark:hover:bg-meta-4">
              <x-icons.dashboard-icon width="18" height="18" />
              Classification News
            </a>
          </li>
        </ul>
      </div>

      {{-- Access Group --}}
      <div>
        <h3 class="mb-4 ml-4 text-sm font-medium text-bodydark2">ACCESS</h3>
        <ul class="mb-6 flex flex-col gap-1.5">
          {{-- Menu Item Dashboard --}}
          <li>
            <a
              href="{{route('cms.access.user.index')}}"
              class="group relative flex items-center gap-2.5 rounded-sm py-2 px-4 font-medium text-bodydark1 duration-300 ease-in-out hover:bg-graydark dark:hover:bg-meta-4"
              >
              <x-icons.dashboard-icon width="18" height="18" />
              User
            </a>
          </li>
          <li>
            <a
              href="{{route('cms.access.role.index')}}"
              class="group relative flex items-center gap-2.5 rounded-sm py-2 px-4 font-medium text-bodydark1 duration-300 ease-in-out hover:bg-graydark dark:hover:bg-meta-4"
              >
              <x-icons.dashboard-icon width="18" height="18" />
              Role
            </a>
          </li>
          <li>
            <a
              href="{{route('cms.access.permission.index')}}"
              class="group relative flex items-center gap-2.5 rounded-sm py-2 px-4 font-medium text-bodydark1 duration-300 ease-in-out hover:bg-graydark dark:hover:bg-meta-4"
              >
              <x-icons.dashboard-icon width="18" height="18" />
              Permission
            </a>
          </li>
        </ul>
      </div>
    </nav>
    {{-- <!-- Sidebar Menu --> --}}
  </div>
</aside>
