<script setup lang="ts">
import { onMounted, onUnmounted, ref, watch } from 'vue'
import { router } from '@inertiajs/vue3'
import {
  CommandDialog,
  CommandEmpty,
  CommandGroup,
  CommandInput,
  CommandItem,
  CommandList,
  CommandSeparator,
} from '@/components/ui/command'
import { Button } from '@/components/ui/button'
import {
  Search,
  Calendar,
  NotebookText,
  ListTodo,
  Tag,
  Star,
  Clock,
  Trash,
  AlertCircle,
} from 'lucide-vue-next'
import { debounce } from 'lodash'
import AdvancedSearch from '@/components/AdvancedSearch.vue'
import { useSearchStore } from '@/stores/useSearchStore'

// Define interfaces for type safety
interface SearchResult {
  id: number
  title?: string
  name?: string
  slug?: string
  type?: string
}

interface SearchResults {
  tasks: SearchResult[]
  projects: SearchResult[]
  notes: SearchResult[]
  tags: SearchResult[]
}

const isOpen = ref(false)
const showAdvancedSearch = ref(false) // Missing ref
const searchQuery = ref('')
const isLoading = ref(false)
const results = ref<SearchResults>({
  tasks: [],
  projects: [],
  notes: [],
  tags: [],
})

const searchStore = useSearchStore() // Add store instance

// Keyboard shortcut to open search
const handleKeydown = (e: KeyboardEvent) => {
  if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
    e.preventDefault()
    isOpen.value = true
  }
}

onMounted(() => {
  window.addEventListener('keydown', handleKeydown)
})

onUnmounted(() => {
  window.removeEventListener('keydown', handleKeydown)
})

const performSearch = debounce(async (query: string) => {
  if (!query) {
    results.value = { tasks: [], projects: [], notes: [], tags: [] }
    return
  }

  isLoading.value = true
  try {
    const response = await fetch(route('api.search'), {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
      },
      body: JSON.stringify({ query }),
    })

    if (!response.ok) {
      throw new Error('Search request failed')
    }

    results.value = await response.json()

    // Add to recent searches
    if (query.trim()) {
      searchStore.addRecentSearch(query)
    }
  } catch (error) {
    console.error('Search failed:', error)
    results.value = { tasks: [], projects: [], notes: [], tags: [] }
  } finally {
    isLoading.value = false
  }
}, 300)

watch(searchQuery, (query) => {
  performSearch(query)
})

const navigateTo = (route: string) => {
  isOpen.value = false
  router.visit(route)
}

const getIcon = (type: string) => {
  const icons = {
    tasks: ListTodo,
    projects: NotebookText,
    notes: NotebookText,
    tags: Tag,
    calendar: Calendar,
    important: Star,
  }
  return icons[type as keyof typeof icons] || AlertCircle
}

// Add missing handleAdvancedSearch function
const handleAdvancedSearch = (filters: Record<string, any>) => {
  // Handle advanced search filters
  router.visit(route('search.advanced'), {
    method: 'get',
    data: filters,
    preserveState: true,
  })
  showAdvancedSearch.value = false
}
</script>

<template>
  <div>
    <Button
      variant="outline"
      class="relative h-9 w-60 justify-start text-sm text-muted-foreground"
      @click="isOpen = true"
    >
      <Search class="mr-2 h-4 w-4" />
      <span>Search...</span>
      <kbd
        class="pointer-events-none absolute right-1.5 top-1.5 hidden h-6 select-none items-center gap-1 rounded border bg-muted px-1.5 font-mono text-[10px] font-medium opacity-100 sm:flex"
      >
        <span class="text-xs">⌘</span>K
      </kbd>
    </Button>

    <CommandDialog :open="isOpen" @update:open="isOpen = $event">
      <CommandInput
        v-model="searchQuery"
        placeholder="Type to search..."
      />
      <CommandList>
        <CommandEmpty v-if="!isLoading && searchQuery">
          No results found.
          <Button
            variant="link"
            class="mt-2"
            @click="showAdvancedSearch = true"
          >
            Try advanced search
          </Button>
        </CommandEmpty>

        <template v-if="!searchQuery && searchStore.recentSearches.length">
          <CommandGroup heading="Recent Searches">
            <CommandItem
              v-for="(search, index) in searchStore.recentSearches"
              :key="index"
              @select="searchQuery = search"
            >
              <Clock class="mr-2 h-4 w-4" />
              <span>{{ search }}</span>
            </CommandItem>

            <CommandItem @select="searchStore.clearRecentSearches">
              <Trash class="mr-2 h-4 w-4" />
              <span>Clear Recent Searches</span>
            </CommandItem>
          </CommandGroup>
          <CommandSeparator />
        </template>

        <div v-if="isLoading" class="p-4 text-sm text-muted-foreground">
          Searching...
        </div>

        <template v-else>
          <!-- Tasks -->
          <CommandGroup v-if="results.tasks.length" heading="Tasks">
            <CommandItem
              v-for="task in results.tasks"
              :key="`task-${task.id}`"
              @select="navigateTo(route('tasks.show', task.id))"
              :value="task.id.toString()"
            >
              <ListTodo class="mr-2 h-4 w-4" />
              <span>{{ task.title }}</span>
            </CommandItem>
          </CommandGroup>

          <!-- Projects -->
          <CommandGroup v-if="results.projects.length" heading="Projects">
            <CommandItem
              v-for="project in results.projects"
              :key="`project-${project.id}`"
              @select="navigateTo(route('projects.show', project.id))"
              :value="project.id.toString()"
            >
              <NotebookText class="mr-2 h-4 w-4" />
              <span>{{ project.name }}</span>
            </CommandItem>
          </CommandGroup>

          <!-- Notes -->
          <CommandGroup v-if="results.notes.length" heading="Notes">
            <CommandItem
              v-for="note in results.notes"
              :key="`note-${note.id}`"
              @select="navigateTo(route('notes.show', note.id))"
              :value="note.id.toString()"
            >
              <NotebookText class="mr-2 h-4 w-4" />
              <span>{{ note.title }}</span>
            </CommandItem>
          </CommandGroup>

          <!-- Tags -->
          <CommandGroup v-if="results.tags.length" heading="Tags">
            <CommandItem
              v-for="tag in results.tags"
              :key="`tag-${tag.id}`"
              @select="navigateTo(route('tags.show', [tag.type, tag.slug]))"
              :value="tag.id.toString()"
            >
              <Tag class="mr-2 h-4 w-4" />
              <span>#{{ tag.name }}</span>
            </CommandItem>
          </CommandGroup>

          <CommandSeparator />

          <CommandGroup>
            <CommandItem @select="showAdvancedSearch = true">
              <Search class="mr-2 h-4 w-4" />
              <span>Advanced Search</span>
            </CommandItem>
          </CommandGroup>
        </template>
      </CommandList>
    </CommandDialog>

    <AdvancedSearch
      v-model:open="showAdvancedSearch"
      @search="handleAdvancedSearch"
    />
  </div>
</template>
