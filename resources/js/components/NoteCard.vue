<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import { formatDistance } from 'date-fns'
import {
  Card,
  CardContent,
  CardFooter,
  CardHeader
} from '@/components/ui/card'
import { Badge } from '@/components/ui/badge'
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar'
import { Button } from '@/components/ui/button'
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger
} from '@/components/ui/dropdown-menu'
import { Lock, MoreVertical, Share2, Globe } from 'lucide-vue-next'
import { computed } from 'vue';

interface Tag {
  id: number
  name: string
  color: string
}

interface Note {
  id: number
  title: string
  content: string
  visibility: 'public' | 'private' | 'shared'
  created_at: string
  tags: Tag[]
  user: {
    id: number
    name: string
    avatar: string
  }
}

const props = defineProps<{
  note: Note
}>()

const visibilityIcon = computed(() => {
  return {
    public: Globe,
    private: Lock,
    shared: Share2
  }[props.note.visibility]
})
</script>

<template>
  <Card class="group">
    <CardHeader class="space-y-4">
      <div class="flex items-start justify-between">
        <div class="space-y-1">
          <h3 class="font-medium leading-none group-hover:text-primary">
            {{ note.title }}
          </h3>
          <div class="flex items-center gap-2 text-sm text-muted-foreground">
            <component :is="visibilityIcon" class="w-3 h-3" />
            <span class="capitalize">{{ note.visibility }}</span>
          </div>
        </div>

        <DropdownMenu>
          <DropdownMenuTrigger asChild>
            <Button variant="ghost" size="icon" class="h-8 w-8">
              <MoreVertical class="h-4 w-4" />
            </Button>
          </DropdownMenuTrigger>
          <DropdownMenuContent align="end">
            <DropdownMenuItem>Edit Note</DropdownMenuItem>
            <DropdownMenuItem class="text-destructive">
              Delete Note
            </DropdownMenuItem>
          </DropdownMenuContent>
        </DropdownMenu>
      </div>

      <div class="flex flex-wrap gap-2">
        <Link
          v-for="tag in note.tags"
          :key="tag.id"
          :href="route('tags.show', tag.id)"
        >
          <Badge
            variant="secondary"
            :class="`hover:bg-${tag.color}-100 hover:text-${tag.color}-600`"
          >
            {{ tag.name }}
          </Badge>
        </Link>
      </div>
    </CardHeader>

    <CardContent>
      <p class="text-sm text-muted-foreground line-clamp-3">
        {{ note.content }}
      </p>
    </CardContent>

    <CardFooter>
      <div class="flex items-center gap-2 text-sm text-muted-foreground">
        <Avatar class="h-6 w-6">
          <AvatarImage :src="note.user.avatar" :alt="note.user.name" />
          <AvatarFallback>{{ note.user.name[0] }}</AvatarFallback>
        </Avatar>
        <span>{{ note.user.name }}</span>
        <span>·</span>
        <time :datetime="note.created_at">
          {{ formatDistance(new Date(note.created_at), new Date(), { addSuffix: true }) }}
        </time>
      </div>
    </CardFooter>
  </Card>
</template>
