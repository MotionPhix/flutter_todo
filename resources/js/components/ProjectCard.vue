<script setup lang="ts">
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import { Card, CardContent, CardFooter, CardHeader } from '@/components/ui/card'
import { Badge } from '@/components/ui/badge'
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar'
import { Button } from '@/components/ui/button'
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu'
import {
  Calendar,
  CheckSquare,
  Clock,
  MoreVertical,
  Tag,
  Users,
} from 'lucide-vue-next'
import { formatDistance } from 'date-fns'

interface Project {
  id: number
  name: string
  description: string | null
  status: 'planning' | 'in_progress' | 'on_hold' | 'completed' | 'cancelled'
  due_date: string | null
  owner: {
    id: number
    name: string
    avatar: string | null
  }
  members: Array<{
    id: number
    name: string
    avatar: string | null
  }>
  tags: Array<{
    id: number
    name: string
    color: string
  }>
  tasks_count: number
  completed_tasks_count: number
  created_at: string
  updated_at: string
}

const props = defineProps<{
  project: Project
}>()

const statusClasses = computed(() => ({
  planning: 'bg-purple-100 text-purple-800',
  in_progress: 'bg-blue-100 text-blue-800',
  on_hold: 'bg-yellow-100 text-yellow-800',
  completed: 'bg-green-100 text-green-800',
  cancelled: 'bg-red-100 text-red-800',
}))

const timeAgo = computed(() =>
  formatDistance(new Date(props.project.created_at), new Date(), { addSuffix: true })
)

const progress = computed(() =>
  props.project.tasks_count > 0
    ? Math.round((props.project.completed_tasks_count / props.project.tasks_count) * 100)
    : 0
)
</script>

<template>
  <Card class="group">
    <CardHeader class="space-y-4">
      <div class="flex items-start justify-between">
        <Link
          :href="route('projects.show', project.id)"
          class="flex-1 font-medium hover:text-primary"
        >
          {{ project.name }}
        </Link>

        <DropdownMenu>
          <DropdownMenuTrigger asChild>
            <Button
              variant="ghost"
              size="icon"
              class="h-8 w-8 opacity-0 group-hover:opacity-100"
            >
              <MoreVertical class="h-4 w-4" />
            </Button>
          </DropdownMenuTrigger>
          <DropdownMenuContent align="end">
            <DropdownMenuItem>Edit Project</DropdownMenuItem>
            <DropdownMenuItem>Manage Members</DropdownMenuItem>
            <DropdownMenuItem class="text-red-600">Archive Project</DropdownMenuItem>
          </DropdownMenuContent>
        </DropdownMenu>
      </div>

      <Badge :class="statusClasses[project.status]">
        {{ project.status.replace('_', ' ') }}
      </Badge>
    </CardHeader>

    <CardContent class="space-y-4">
      <p v-if="project.description" class="text-sm text-muted-foreground line-clamp-2">
        {{ project.description }}
      </p>

      <!-- Project Tags -->
      <div v-if="project.tags.length" class="flex flex-wrap gap-1">
        <Badge
          v-for="tag in project.tags"
          :key="tag.id"
          variant="outline"
          class="text-xs"
          :style="{ color: tag.color }"
        >
          #{{ tag.name }}
        </Badge>
      </div>

      <!-- Tasks Progress -->
      <div v-if="project.tasks_count > 0" class="space-y-1">
        <div class="flex items-center justify-between text-xs text-muted-foreground">
          <span class="flex items-center gap-1">
            <CheckSquare class="h-3 w-3" />
            {{ project.completed_tasks_count }} / {{ project.tasks_count }} tasks
          </span>
          <span>{{ progress }}%</span>
        </div>
        <div class="h-1.5 w-full rounded-full bg-secondary">
          <div
            class="h-full rounded-full bg-primary transition-all"
            :style="{ width: `${progress}%` }"
          />
        </div>
      </div>

      <!-- Project Members -->
      <div class="flex -space-x-2">
        <Avatar
          v-for="member in project.members.slice(0, 4)"
          :key="member.id"
          class="h-6 w-6 border-2 border-background"
        >
          <AvatarImage :src="member.avatar" :alt="member.name" />
          <AvatarFallback>{{ member.name.charAt(0) }}</AvatarFallback>
        </Avatar>
        <div
          v-if="project.members.length > 4"
          class="flex h-6 w-6 items-center justify-center rounded-full border-2 border-background bg-muted text-xs"
        >
          +{{ project.members.length - 4 }}
        </div>
      </div>
    </CardContent>

    <CardFooter>
      <div class="flex items-center justify-between w-full text-sm text-muted-foreground">
        <div class="flex items-center gap-2">
          <Avatar class="h-6 w-6">
            <AvatarImage :src="project.owner.avatar" :alt="project.owner.name" />
            <AvatarFallback>{{ project.owner.name.charAt(0) }}</AvatarFallback>
          </Avatar>
          <span>{{ project.owner.name }}</span>
        </div>

        <div class="flex items-center gap-4">
          <span v-if="project.due_date" class="flex items-center gap-1">
            <Calendar class="h-3 w-3" />
            {{ new Date(project.due_date).toLocaleDateString() }}
          </span>
          <span class="flex items-center gap-1">
            <Clock class="h-3 w-3" />
            {{ timeAgo }}
          </span>
        </div>
      </div>
    </CardFooter>
  </Card>
</template>
