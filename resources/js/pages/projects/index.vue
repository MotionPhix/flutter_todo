<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { useToast } from '@/hooks/useToast';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Plus } from 'lucide-vue-next';
import ProjectCard from './Partials/ProjectCard.vue';

const { toast } = useToast();

defineProps<{
  projects: {
    data: {
      id: number;
      name: string;
      description: string | null;
      status: 'active' | 'archived';
      owner: {
        id: number;
        name: string;
        avatar: string;
      };
      members: Array<{
        id: number;
        name: string;
        avatar: string;
      }>;
      tasks_count: number;
      created_at: string;
    }[];
    meta: {
      current_page: number;
      last_page: number;
      total: number;
    };
  };
}>();
</script>

<template>
  <AppLayout>
    <Head title="Projects" />

    <div class="container py-6">
      <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">Projects</h1>

        <Link :href="route('projects.create')">
          <Button>
            <Plus class="w-4 h-4 mr-2" />
            New Project
          </Button>
        </Link>
      </div>

      <div v-if="projects.data.length" class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
        <ProjectCard
          v-for="project in projects.data"
          :key="project.id"
          :project="project"
        />
      </div>
      <div v-else class="text-center py-12">
        <p class="text-muted-foreground">No projects found. Create your first project!</p>
      </div>
    </div>
  </AppLayout>
</template>
