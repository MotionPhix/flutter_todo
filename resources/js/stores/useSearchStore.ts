import { defineStore } from 'pinia'
import { ref, computed } from 'vue'

export const useSearchStore = defineStore('search', () => {
  // Search state
  const recentSearches = ref<string[]>([])
  const filters = ref({
    type: 'all', // all, tasks, projects, notes, tags
    date: {
      from: null as string | null,
      to: null as string | null,
    },
    status: [] as string[],
    priority: [] as string[],
    assignee: null as number | null,
    list: null as number | null,
    tags: [] as number[],
  })

  // Add search to history
  const addToRecent = (query: string) => {
    if (!recentSearches.value.includes(query)) {
      recentSearches.value.unshift(query)
      if (recentSearches.value.length > 5) {
        recentSearches.value.pop()
      }
      localStorage.setItem('recentSearches', JSON.stringify(recentSearches.value))
    }
  }

  // Load recent searches from localStorage
  const loadRecentSearches = () => {
    const saved = localStorage.getItem('recentSearches')
    if (saved) {
      recentSearches.value = JSON.parse(saved)
    }
  }

  // Clear recent searches
  const clearRecentSearches = () => {
    recentSearches.value = []
    localStorage.removeItem('recentSearches')
  }

  return {
    recentSearches,
    filters,
    addToRecent,
    loadRecentSearches,
    clearRecentSearches,
  }
})
