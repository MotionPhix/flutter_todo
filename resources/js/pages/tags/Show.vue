<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import { computed } from 'vue'
import AppLayout from '@/layouts/AppLayout.vue'
import { Badge } from '@/components/ui/badge'
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs'
import TaskCard from '@/components/TaskCard.vue'
import ProjectCard from '@/components/ProjectCard.vue'
import NoteCard from '@/components/NoteCard.vue'
import EmptyState from '@/components/EmptyState.vue'

interface Tag {
  id: number
  name: string
  type: string
  color: string
  slug: string
}

interface Item {
  id: number
  type: 'Task' | 'Project' | 'Note'
  title: string
  description: string | null
  count?: number
  created_at: string
  user: {
    id: number
    name: string
    avatar: string
  }
  route: string
}

const props = defineProps<{
  tag: Tag
  items: Item[]
}>()

const itemsByType = computed(() => {
  return {
    tasks: props.items.filter(item => item.type === 'Task'),
    projects: props.items.filter(item => item.type === 'Project'),
    notes: props.items.filter(item => item.type === 'Note'),
  }
})
</script>

<template>
  <AppLayout>
    <Head :title="`#${tag.name}`" />

    <div class="container py-6">
      <div class="flex items-center gap-4 mb-6">
        <h1 class="text-2xl font-semibold">
          #{{ tag.name }}
        </h1>
        <Badge
          variant="outline"
          class="capitalize"
        >
          {{ tag.type }}
        </Badge>
      </div>

      <Tabs defaultValue="all" class="space-y-4">
        <TabsList>
          <TabsTrigger value="all">
            All ({{ items.length }})
          </TabsTrigger>
          <TabsTrigger value="tasks">
            Tasks ({{ itemsByType.tasks.length }})
          </TabsTrigger>
          <TabsTrigger value="projects">
            Projects ({{ itemsByType.projects.length }})
          </TabsTrigger>
          <TabsTrigger value="notes">
            Notes ({{ itemsByType.notes.length }})
          </TabsTrigger>
        </TabsList>

        <TabsContent value="all">
          <div v-if="items.length" class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            <template v-for="item in items" :key="item.id">
              <TaskCard v-if="item.type === 'Task'" :task="item" />
              <ProjectCard v-else-if="item.type === 'Project'" :project="item" />
              <NoteCard v-else :note="item" />
            </template>
          </div>
          <EmptyState
            v-else
            title="No tagged items"
            description="There are no items tagged with this label yet."
          />
        </TabsContent>

        <TabsContent value="tasks">
          <div v-if="itemsByType.tasks.length" class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            <TaskCard
              v-for="task in itemsByType.tasks"
              :key="task.id"
              :task="task"
            />
          </div>
          <EmptyState
            v-else
            title="No tasks"
            description="There are no tasks tagged with this label yet."
          />
        </TabsContent>

        <TabsContent value="projects">
          <div v-if="itemsByType.projects.length" class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            <ProjectCard
              v-for="project in itemsByType.projects"
              :key="project.id"
              :project="project"
            />
          </div>
          <EmptyState
            v-else
            title="No projects"
            description="There are no projects tagged with this label yet."
          />
        </TabsContent>

        <TabsContent value="notes">
          <div v-if="itemsByType.notes.length" class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            <NoteCard
              v-for="note in itemsByType.notes"
              :key="note.id"
              :note="note"
            />
          </div>
          <EmptyState
            v-else
            title="No notes"
            description="There are no notes tagged with this label yet."
          />
        </TabsContent>
      </Tabs>
    </div>
  </AppLayout>
</template>
