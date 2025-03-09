<script setup lang="ts">
import AppContent from '@/components/AppContent.vue';
import AppShell from '@/components/AppShell.vue';
import AppSidebarHeader from '@/components/AppSidebarHeader.vue';
import { Button } from '@/components/ui/button';
import {
  Sidebar,
  SidebarContent,
  SidebarGroup,
  SidebarGroupContent,
  SidebarGroupLabel,
  SidebarHeader,
  SidebarMenu,
  SidebarMenuItem,
  SidebarMenuButton,
  SidebarSeparator
} from '@/components/ui/sidebar';
import type { BreadcrumbItemType } from '@/types';
import { Link } from '@inertiajs/vue3';
import {
  Briefcase,
  CheckSquare,
  ChevronDown,
  Clock,
  Inbox,
  LayoutGrid,
  Plus,
  Star,
  Users
} from 'lucide-vue-next';
import { ref } from 'vue';
import AppLogo from '@/components/AppLogo.vue';
import ScrollArea from '@/components/ScrollArea.vue';

interface Props {
  breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
  breadcrumbs: () => [],
});

const projects = ref([
  { id: 1, name: 'Marketing Campaign', count: 12 },
  { id: 2, name: 'Website Redesign', count: 8 },
  { id: 3, name: 'Mobile App', count: 15 }
]);

const mainNavItems = [
  {
    title: 'Dashboard',
    href: '/dashboard',
    icon: LayoutGrid,
  },
  {
    title: 'My Tasks',
    href: '/tasks',
    icon: CheckSquare,
  },
  {
    title: 'Projects',
    href: '/projects',
    icon: Briefcase,
  },
  {
    title: 'Team',
    href: '/team',
    icon: Users,
  }
];

const taskFilters = [
  {
    title: 'Inbox',
    href: '/tasks/inbox',
    icon: Inbox,
    count: 12
  },
  {
    title: 'Today',
    href: '/tasks/today',
    icon: Clock,
    count: 5
  },
  {
    title: 'Important',
    href: '/tasks/important',
    icon: Star,
    count: 3
  }
];
</script>

<template>
  <AppShell variant="sidebar">
    <!-- Main Sidebar -->
    <Sidebar collapsible="icon" class="flex flex-col border-r">
      <SidebarHeader class="border-b px-2 py-2">
        <SidebarMenu>
          <SidebarMenuItem>
            <SidebarMenuButton size="lg" as-child>
              <Link :href="route('dashboard')">
                <AppLogo />
              </Link>
            </SidebarMenuButton>
          </SidebarMenuItem>
        </SidebarMenu>
      </SidebarHeader>

      <ScrollArea class="flex-1 px-2 py-2">
        <SidebarContent>
          <!-- Main Navigation -->
          <SidebarMenu>
            <SidebarMenuItem v-for="item in mainNavItems" :key="item.title">
              <SidebarMenuButton :tooltip="item.title" as-child>
                <Link :href="item.href" class="flex w-full items-center">
                  <component :is="item.icon" class="mr-2" />
                  <span>{{ item.title }}</span>
                </Link>
              </SidebarMenuButton>
            </SidebarMenuItem>
          </SidebarMenu>

          <SidebarSeparator class="my-4" />

          <!-- Task Filters -->
          <SidebarMenu>
            <SidebarMenuItem v-for="filter in taskFilters" :key="filter.title">
              <SidebarMenuButton :tooltip="filter.title" as-child>
                <Link :href="filter.href" class="flex w-full items-center justify-between">
                  <span class="flex items-center">
                    <component :is="filter.icon" class="mr-2" />
                    <span>{{ filter.title }}</span>
                  </span>
                  <span class="rounded-full bg-secondary px-2 py-0.5 text-xs">
                    {{ filter.count }}
                  </span>
                </Link>
              </SidebarMenuButton>
            </SidebarMenuItem>
          </SidebarMenu>

          <SidebarSeparator class="my-4" />

          <!-- Projects Section -->
          <SidebarGroup>
            <SidebarGroupLabel class="flex items-center justify-between px-2">
              <span class="text-xs font-medium">Projects</span>
              <Button variant="ghost" size="icon" class="h-6 w-6">
                <Plus class="h-4 w-4" />
              </Button>
            </SidebarGroupLabel>
            <SidebarGroupContent>
              <SidebarMenu>
                <SidebarMenuItem v-for="project in projects" :key="project.id">
                  <SidebarMenuButton :tooltip="project.name" as-child>
                    <Link :href="`/projects/${project.id}`" class="flex w-full items-center justify-between">
                      <span class="flex items-center space-x-2">
                        <ChevronDown class="h-4 w-4" />
                        <span>{{ project.name }}</span>
                      </span>
                      <span class="rounded-full bg-secondary px-2 py-0.5 text-xs">
                        {{ project.count }}
                      </span>
                    </Link>
                  </SidebarMenuButton>
                </SidebarMenuItem>
              </SidebarMenu>
            </SidebarGroupContent>
          </SidebarGroup>
        </SidebarContent>
      </ScrollArea>
    </Sidebar>

    <!-- Main Content Area -->
    <AppContent variant="sidebar">
      <AppSidebarHeader :breadcrumbs="breadcrumbs" />
      <slot />
    </AppContent>
  </AppShell>
</template>
