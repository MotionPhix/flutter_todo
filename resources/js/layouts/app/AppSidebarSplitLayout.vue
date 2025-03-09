<script setup lang="ts">
import { computed, ref } from 'vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Separator } from '@/components/ui/separator';
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuLabel,
  DropdownMenuSeparator,
  DropdownMenuTrigger
} from '@/components/ui/dropdown-menu';
import {
  Bell,
  ChevronRight,
  Home,
  LogOut,
  Settings,
  Star,
  SunMedium,
  Plus,
  Trash2,
  User,
  Calendar,
  ListTodo,
  NotebookText
} from 'lucide-vue-next';
import ScrollArea from '@/components/ScrollArea.vue';
import GlobalSearch from '@/components/GlobalSearch.vue';
import SortableList from '@/components/SortableList.vue';
import SortableItem from '@/components/SortableItem.vue';

const page = usePage();
const user = page.props.auth.user;
const isListsExpanded = ref(true);
const isLabelsExpanded = ref(true);

const form = useForm({});

const navigation = [
  { name: 'Dashboard', href: route('dashboard'), icon: Home },
  { name: 'Today', href: route('tasks.index', { filter: 'today' }), icon: SunMedium },
  { name: 'Notes', href: route('notes.index'), icon: NotebookText },
  { name: 'Calendar', href: '#', icon: Calendar },
  { name: 'Important', href: route('tasks.index', { filter: 'important' }), icon: Star },
  { name: 'Projects', href: route('projects.index'), icon: ListTodo }
];

// Lists section
const lists = computed(() => page.props.lists as Array<{
  id: number
  name: string
  tasks_count: number
}>);

// Labels section
const labels = computed(() => page.props.labels as Array<{
  id: number
  name: string
  type: string
  color: string
  count: number
}>);

const handleListReorder = (reorderedLists) => {
  form.patch(route('lists.reorder'), {
    lists: reorderedLists.map(list => list.id)
  });
};
</script>

<template>
  <div class="flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <div class="w-64 flex-shrink-0 border-r bg-card flex flex-col">
      <!-- Logo -->
      <div class="h-14 flex items-center px-4 border-b">
        <Link href="/" class="font-semibold">TaskHub</Link>
      </div>

      <!-- Navigation -->
      <ScrollArea class="flex-1">
        <div class="p-2 space-y-6">
          <!-- Main Navigation -->
          <nav class="space-y-1">
            <Link
              v-for="item in navigation"
              :key="item.name"
              :href="item.href"
              class="flex items-center gap-x-3 px-3 py-2 text-sm font-medium rounded-md transition-colors hover:bg-accent hover:text-accent-foreground"
              :class="{ 'bg-accent text-accent-foreground': route().current(item.name.toLowerCase()) }">
              <component :is="item.icon" class="w-4 h-4" />
              {{ item.name }}
              <span v-if="item.name === 'Tasks'" class="ml-auto text-xs text-muted-foreground">24</span>
            </Link>
          </nav>

          <Separator />

          <!-- Projects Section -->
          <!--          <div class="space-y-2">-->
          <!--            <div class="px-3">-->
          <!--              <div class="flex items-center justify-between">-->
          <!--                <button-->
          <!--                  class="flex items-center gap-x-1 text-xs font-medium uppercase text-muted-foreground hover:text-foreground"-->
          <!--                  @click="isProjectsExpanded = !isProjectsExpanded"-->
          <!--                >-->
          <!--                  <ChevronRight-->
          <!--                    class="w-4 h-4 transition-transform"-->
          <!--                    :class="{ 'rotate-90': isProjectsExpanded }"-->
          <!--                  />-->
          <!--                  Projects-->
          <!--                </button>-->

          <!--                <Button variant="ghost" size="icon" class="h-6 w-6">-->
          <!--                  <Plus class="w-4 h-4" />-->
          <!--                </Button>-->
          <!--              </div>-->
          <!--            </div>-->

          <!--            <div v-show="isProjectsExpanded" class="space-y-1">-->
          <!--              <Link-->
          <!--                v-for="project in projects"-->
          <!--                :key="project.id"-->
          <!--                :href="route('projects.show', project.id)"-->
          <!--                class="flex items-center justify-between px-3 py-2 text-sm rounded-md transition-colors hover:bg-accent hover:text-accent-foreground"-->
          <!--              >-->
          <!--                <span>{{ project.name }}</span>-->
          <!--                <span class="text-xs text-muted-foreground">{{ project.count }}</span>-->
          <!--              </Link>-->
          <!--            </div>-->
          <!--          </div>-->

          <!--          <div class="grid grid-cols-2 gap-2">-->
          <!--            <Link-->
          <!--              v-for="item in navigation"-->
          <!--              :key="item.name"-->
          <!--              :href="item.href"-->
          <!--              class="flex flex-col items-center justify-center p-3 rounded-md transition-colors hover:bg-accent hover:text-accent-foreground text-center"-->
          <!--              :class="{ 'bg-accent text-accent-foreground': route().current(item.name.toLowerCase()) }">-->
          <!--              <component :is="item.icon" class="w-5 h-5 mb-1" />-->
          <!--              <span class="text-xs font-medium">{{ item.name }}</span>-->
          <!--            </Link>-->
          <!--          </div>-->

          <!-- Lists Section -->
          <div class="space-y-2">
            <div class="px-3">
              <div class="flex items-center justify-between">
                <button
                  class="flex items-center gap-x-1 text-xs font-medium uppercase text-muted-foreground hover:text-foreground"
                  @click="isListsExpanded = !isListsExpanded">
                  <ChevronRight
                    class="w-4 h-4 transition-transform"
                    :class="{ 'rotate-90': isListsExpanded }"
                  />
                  Lists
                </button>

                <ModalLink
                  as="Button"
                  variant="ghost" size="icon" class="h-6 w-6"
                  :href="route('lists.create')">
                  <Plus class="w-4 h-4" />
                </ModalLink>
              </div>
            </div>

            <div v-show="isListsExpanded" class="space-y-1">
              <SortableList
                :items="lists"
                @reorder="handleListReorder">
                <SortableItem
                  v-for="list in lists"
                  :key="list.id"
                  :id="list.id">
                  <Link
                    v-for="list in lists"
                    :key="list.id"
                    :href="route('lists.show', list.id)"
                    class="flex items-center justify-between px-3 py-2 text-sm rounded-md transition-colors hover:bg-accent hover:text-accent-foreground">
                    <span>{{ list.name }}</span>

                    <div class="flex items-center gap-2">
                      <!--span class="text-xs text-muted-foreground">
                        {{ list.count }}
                      </span-->

                      <span class="text-xs text-muted-foreground">
                    {{ list.tasks_count }}
                  </span>

                      <Button
                        variant="ghost"
                        size="icon"
                        class="h-6 w-6 opacity-0 group-hover:opacity-100">
                        <Settings class="h-4 w-4" />
                      </Button>
                    </div>
                  </Link>
                </SortableItem>
              </SortableList>
            </div>
          </div>

          <Separator />

          <!-- Labels Section -->
          <div class="space-y-2">
            <div class="px-3">
              <div class="flex items-center justify-between">
                <button
                  class="flex items-center gap-x-1 text-xs font-medium uppercase text-muted-foreground hover:text-foreground"
                  @click="isLabelsExpanded = !isLabelsExpanded">
                  <ChevronRight
                    class="w-4 h-4 transition-transform"
                    :class="{ 'rotate-90': isLabelsExpanded }"
                  />
                  Labels
                </button>

                <!-- ... -->
                <ModalLink
                  as="Button"
                  :href="route('labels.create')"
                  variant="ghost" size="icon" class="h-6 w-6">
                  <Plus class="w-4 h-4" />
                </ModalLink>
              </div>
            </div>

            <div v-show="isLabelsExpanded" class="space-y-1">
              <Link
                v-for="label in labels"
                :key="label.id"
                :href="route('labels.show', label.id)"
                class="flex items-center px-3 py-2 text-sm rounded-md transition-colors hover:bg-accent hover:text-accent-foreground">
                <div class="flex items-center gap-2">
                  <div
                    class="w-2 h-2 rounded-full"
                    :style="{ backgroundColor: label.color }"
                  />
                  <span>{{ label.name }}</span>
                </div>

                <div class="flex items-center gap-2">
                  <span class="text-xs text-muted-foreground">{{ label.count }}</span>
                  <Button
                    variant="ghost"
                    size="icon"
                    class="h-6 w-6 opacity-0 group-hover:opacity-100">
                    <Settings class="h-4 w-4" />
                  </Button>
                </div>
              </Link>
            </div>
          </div>
        </div>
      </ScrollArea>

      <!-- Bottom Links -->
      <div class="p-2 border-t">
        <Link
          href="/trash"
          class="flex items-center gap-x-3 px-3 py-2 text-sm font-medium rounded-md text-muted-foreground transition-colors hover:bg-accent hover:text-accent-foreground">
          <Trash2 class="w-4 h-4" />
          Trash
        </Link>
      </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
      <!-- Top Navigation Bar -->
      <header class="h-14 bg-background flex items-center justify-end px-4 gap-4">
        <div class="container flex h-14 items-center justify-between">
          <div class="flex items-center gap-4">
            <GlobalSearch />
          </div>

          <div class="flex items-center gap-4">
            <!-- Notifications -->
            <Button variant="ghost" size="icon" class="relative">
              <Bell class="w-5 h-5" />
              <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full" />
            </Button>

            <!-- User Menu -->
            <DropdownMenu>
              <DropdownMenuTrigger>
                <Avatar class="h-8 w-8">
                  <AvatarImage :src="user.avatar" :alt="user.name" />
                  <AvatarFallback>{{ user.name[0] }}</AvatarFallback>
                </Avatar>
              </DropdownMenuTrigger>
              <DropdownMenuContent align="end" class="w-56">
                <DropdownMenuLabel>
                  <div class="flex flex-col space-y-1">
                    <p class="text-sm font-medium leading-none">{{ user.name }}</p>
                    <p class="text-xs leading-none text-muted-foreground">{{ user.email }}</p>
                  </div>
                </DropdownMenuLabel>
                <DropdownMenuSeparator />
                <DropdownMenuItem>
                  <User class="w-4 h-4 mr-2" />
                  Profile
                </DropdownMenuItem>
                <DropdownMenuItem>
                  <Settings class="w-4 h-4 mr-2" />
                  Settings
                </DropdownMenuItem>
                <DropdownMenuSeparator />
                <DropdownMenuItem>
                  <LogOut class="w-4 h-4 mr-2" />
                  Log out
                </DropdownMenuItem>
              </DropdownMenuContent>
            </DropdownMenu>
          </div>
        </div>
      </header>

      <!-- Main Content Area -->
      <main class="flex-1 overflow-auto bg-background">
        <slot />
      </main>
    </div>
  </div>
</template>

<style scoped>
.grid {
  grid-template-columns: repeat(2, minmax(0, 1fr));
}
</style>
