<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import { computed } from 'vue'
import AppLayout from '@/layouts/app/AppLayout.vue'
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs'
import { Button } from '@/components/ui/button'
import TaskCard from '@/components/TaskCard.vue'
import ProjectCard from '@/components/ProjectCard.vue'
import NoteCard from '@/components/NoteCard.vue'
import { Filter } from 'lucide-vue-next'

const props = defineProps<{
  results: {
    tasks: any[]
    projects: any[]
    notes: any[]
    tags: any[]
  }
  filters: any
}>()

const totalResults = computed(() =>
  props.results.tasks.length +
  props.results.projects.length +
  props.results.notes.length +
  props.results.tags.length
)
</script>

<template>
  <AppLayout>
    <Head title="Search Results" />

    <div class="container py-6">
      <div class="flex items-center justify-between mb-6">
        <div>
          <h1 class="text-2xl font-semibold">Search Results</h1>
          <p class="text-sm text-muted-foreground mt-1">
            Found {{ totalResults }} results
          </p>
        </div>

        <Button variant="outline" @click="$refs.advancedSearch.show()">
          <Filter class="w-4 h-4 mr-2" />
          Filter Results
        </Button>
      </div>

      <Tabs defaultValue="all" class="space-y-4">
        <TabsList>
          <TabsTrigger value="all">
            All ({{ totalResults }})
          </TabsTrigger>
          <TabsTrigger value="tasks">
            Tasks ({{ results.tasks.length }})
          </TabsTrigger>
          <TabsTrigger value="projects">
            Projects ({{ results.projects.length }})
          </TabsTrigger>
          <TabsTrigger value="notes">
            Notes ({{ results.notes.length }})
          </TabsTrigger>
          <TabsTrigger value="tags">
            Tags ({{ results.tags.length }})
          </TabsTrigger>
        </TabsList>

        <!-- Add tab content for each type -->
        <!-- ... Similar to the Tags/Show.vue implementation ... -->
      </Tabs>
    </div>

    <AdvancedSearchDialog ref="advancedSearch" />
  </AppLayout>
</template>
