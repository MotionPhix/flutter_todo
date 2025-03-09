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
  Star,
  Tag,
} from 'lucide-vue-next'
import { formatDistance } from 'date-fns'

interface Task {
  id: number
  title: string
  description: string | null
  due_date: string | null
  priority: 'low' | 'medium' | 'high' | 'urgent'
  status: 'todo' | 'in_progress' | 'done' | 'cancelled'
  is_important: boolean
  project?: {
    id: number
    name: string
  }
  user: {
    id: number
    name: string
    avatar: string | null
  }
  tags: Array<{
    id: number
    name: string
    color: string
  }>
  subtasks_count: number
  completed_subtasks_count: number
  created_at: string
  updated_at: string
}

const props = defineProps<{
  task: Task
}>()

const priorityClasses = computed(() => ({
  urgent: 'bg-red-100 text-red-800',
  high: 'bg-orange-100 text-orange-800',
  medium: 'bg-yellow-100 text-yellow-800',
  low: 'bg-green-100 text-green-800',
}))

const statusClasses = computed(() => ({
  todo: 'bg-slate-100 text-slate-800',
  in_progress: 'bg-blue-100 text-blue-800',
  done: 'bg-green-100 text-green-800',
  cancelled: 'bg-red-100 text-red-800',
}))

const timeAgo = computed(() =>
  formatDistance(new Date(props.task.created_at), new Date(), { addSuffix: true })
)

const subtasksProgress = computed(() =>
  props.task.subtasks_count > 0
    ? Math.round((props.task.completed_subtasks_count / props.task.subtasks_count) * 100)
    : 0
)
</script>

<template>
  <Card class="group">
    <CardHeader class="space-y-4">
      <div class="flex items-start justify-between">
        <Link
          :href="route('tasks.show', task.id)"
          class="flex-1 font-medium hover:text-primary"
        >
          {{ task.title }}
        </Link>

        <div class="flex items-center gap-2">
          <Button
            v-if="!task.is_important"
            variant="ghost"
            size="icon"
            class="h-8 w-8 opacity-0 group-hover:opacity-100"
            @click="$inertia.patch(route('tasks.toggle-important', task.id))"
          >
            <Star class="h-4 w-4" />
          </Button>
          <Button
            v-else
            variant="ghost"
            size="icon"
            class="h-8 w-8 text-yellow-500"
            @click="$inertia.patch(route('tasks.toggle-important', task.id))"
          >
            <Star class="h-4 w-4 fill-current" />
          </Button>

          <DropdownMenu>
            <DropdownMenuTrigger asChild>
              <Button variant="ghost" size="icon" class="h-8 w-8">
                <MoreVertical class="h-4 w-4" />
              </Button>
            </DropdownMenuTrigger>
            <DropdownMenuContent align="end">
              <DropdownMenuItem>Edit Task</DropdownMenuItem>
              <DropdownMenuItem>Move to List</DropdownMenuItem>
              <DropdownMenuItem class="text-red-600">Delete</DropdownMenuItem>
            </DropdownMenuContent>
          </DropdownMenu>
        </div>
      </div>

      <div class="flex flex-wrap gap-2">
        <Badge :class="priorityClasses[task.priority]">
          {{ task.priority }}
        </Badge>
        <Badge :class="statusClasses[task.status]">
          {{ task.status.replace('_', ' ') }}
        </Badge>
        <Badge v-if="task.project" variant="outline">
          {{ task.project.name }}
        </Badge>
      </div>
    </CardHeader>

    <CardContent class="space-y-4">
      <p v-if="task.description" class="text-sm text-muted-foreground line-clamp-2">
        {{ task.description }}
      </p>

      <div v-if="task.tags.length" class="flex flex-wrap gap-1">
        <Badge
          v-for="tag in task.tags"
          :key="tag.id"
          variant="outline"
          class="text-xs"
          :style="{ color: tag.color }"
        >
          #{{ tag.name }}
        </Badge>
      </div>

      <!-- Subtasks Progress -->
      <div v-if="task.subtasks_count > 0" class="space-y-1">
        <div class="flex items-center justify-between text-xs text-muted-foreground">
          <span class="flex items-center gap-1">
            <CheckSquare class="h-3 w-3" />
            {{ task.completed_subtasks_count }} / {{ task.subtasks_count }}
          </span>
          <span>{{ subtasksProgress }}%</span>
        </div>
        <div class="h-1.5 w-full rounded-full bg-secondary">
          <div
            class="h-full rounded-full bg-primary transition-all"
            :style="{ width: `${subtasksProgress}%` }"
          />
        </div>
      </div>
    </CardContent>

    <CardFooter>
      <div class="flex items-center justify-between w-full text-sm text-muted-foreground">
        <div class="flex items-center gap-2">
          <Avatar class="h-6 w-6">
            <AvatarImage :src="task.user.avatar" :alt="task.user.name" />
            <AvatarFallback>
              {{ task.user.name.charAt(0) }}
            </AvatarFallback>
          </Avatar>
          <span>{{ task.user.name }}</span>
        </div>

        <div class="flex items-center gap-4">
          <span v-if="task.due_date" class="flex items-center gap-1">
            <Calendar class="h-3 w-3" />
            {{ new Date(task.due_date).toLocaleDateString() }}
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
