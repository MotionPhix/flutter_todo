import { ref, computed } from 'vue'

interface Operation<T> {
  type: 'move' | 'add' | 'remove' | 'reorder'
  data: T
  timestamp: number
  previousState: T[]
  currentState: T[]
}

export function useUndoRedo<T>(initialState: T[] = []) {
  const currentState = ref<T[]>(initialState)
  const undoStack = ref<Operation<T>[]>([])
  const redoStack = ref<Operation<T>[]>([])

  const canUndo = computed(() => undoStack.value.length > 0)
  const canRedo = computed(() => redoStack.value.length > 0)

  const addOperation = (operation: Omit<Operation<T>, 'timestamp'>) => {
    undoStack.value.push({
      ...operation,
      timestamp: Date.now()
    })
    redoStack.value = [] // Clear redo stack when new operation is performed
  }

  const undo = () => {
    if (!canUndo.value) return

    const operation = undoStack.value.pop()!
    redoStack.value.push(operation)
    currentState.value = operation.previousState

    return operation
  }

  const redo = () => {
    if (!canRedo.value) return

    const operation = redoStack.value.pop()!
    undoStack.value.push(operation)
    currentState.value = operation.currentState

    return operation
  }

  return {
    currentState,
    addOperation,
    undo,
    redo,
    canUndo,
    canRedo
  }
}
