<script setup lang="ts">
import { computed, ref } from 'vue';
import { Link, useForm, usePage, router } from '@inertiajs/vue3';
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
  GripVertical,
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

const page = usePage();
const user = page.props.auth.user;
const isListsExpanded = ref(true);
const isLabelsExpanded = ref(true);
const isDragging = ref(false)

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
  // is_favorite: boolean
}>);

const favorites = computed(() =>
  lists.value.filter(list => list.is_favorite)
)

const regularLists = computed(() =>
  lists.value.filter(list => !list.is_favorite)
)

const handleListMove = (evt) => {
  // Prevent dragging between favorite and regular lists if needed
  /*if (evt.draggedContext.element.is_favorite !== evt.relatedContext.element?.is_favorite) {
    return false
  }*/

  return true
}


// Labels section
const labels = computed(() => page.props.labels as Array<{
  id: number
  name: string
  type: string
  color: string
  count: number
}>);

const handleListReorder = (reorderedLists) => {
  lists.value = reorderedLists

  // Call your API to update the order
  form.post(route('lists.reorder'), {
    lists: reorderedLists.map(list => list.id)
  })
}

const editList = (list: any) => {
  // Navigate to edit modal
  router.get(route('lists.edit', list.id))
}

const toggleFavorite = async (list: any) => {
  await form.put(route('lists.toggle-favorite', list.id), {
    preserveScroll: true
  })
}

const deleteList = async (list: any) => {
  if (confirm('Are you sure you want to delete this list?')) {
    await form.delete(route('lists.destroy', list.id), {
      preserveScroll: true
    })
  }
}
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
          <section>
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

                <!-- ... -->
                <ModalLink
                  as="Button"
                  :href="route('lists.create')"
                  variant="ghost" size="icon" class="h-6 w-6">
                  <Plus class="w-4 h-4" />
                </ModalLink>
              </div>
            </div>

            <div v-show="isListsExpanded" class="space-y-1">
              <SortableList
                v-model="lists"
                handle
                group="regular"
                :animation="300"
                @start="isDragging = true"
                @end="isDragging = false"
                @move="handleListMove"
                @update="handleListReorder"
              >
                <template #default="{ item: list, isDragover }">
                  <Link
                    :key="list.id"
                    :href="route('lists.show', list.id)"
                    :class="[
                    'flex items-center justify-between px-3 py-2 text-sm rounded-md transition-colors hover:bg-accent group',
                    { 'bg-accent text-accent-foreground': route().params.list == list.id },
                    { 'cursor-grabbing': isDragging },
                    { 'bg-accent/50': isDragover }
                  ]">
                    <div class="flex items-center gap-2">
                      <GripVertical
                        class="handle"
                        :class="[
                        'w-4 h-4 text-muted-foreground/50',
                        isDragging ? 'cursor-grabbing' : 'cursor-move'
                      ]"
                      />
                      <span>{{ list.name }}</span>
                    </div>

                    <div class="flex items-center gap-2">
                    <span
                      v-if="list.tasks_count"
                      class="text-xs text-muted-foreground">
                      {{ list.tasks_count }}
                    </span>

                      <DropdownMenu>
                        <DropdownMenuTrigger asChild>
                          <Button
                            variant="ghost"
                            size="icon"
                            class="h-6 w-6 opacity-0 group-hover:opacity-100">
                            <Settings class="h-4 w-4" />
                          </Button>
                        </DropdownMenuTrigger>

                        <DropdownMenuContent align="end">
                          <DropdownMenuItem @click="editList(list)">
                            Edit
                          </DropdownMenuItem>

                          <DropdownMenuItem @click="toggleFavorite(list)">
                            {{ list.is_favorite ? 'Remove from favorites' : 'Add to favorites' }}
                          </DropdownMenuItem>

                          <DropdownMenuSeparator />

                          <DropdownMenuItem
                            class="text-destructive focus:text-destructive"
                            @click="deleteList(list)">
                            Delete
                          </DropdownMenuItem>
                        </DropdownMenuContent>
                      </DropdownMenu>
                    </div>
                  </Link>
                </template>
              </SortableList>
            </div>
          </section>

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
                  :href="route('tags.create')"
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
