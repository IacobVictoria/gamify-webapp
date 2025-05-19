<template>
    <AuthenticatedLayout>
      <div class="py-12">
        <div class="w-full max-w-4xl mx-auto bg-white shadow-md rounded-lg p-6">
          <h2 class="text-2xl font-semibold text-gray-800 mb-6 flex items-center gap-2">
            ✏️ Editează Activitate
          </h2>
  
          <form @submit.prevent="submit">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700">Titlu</label>
                <input v-model="form.title" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
              </div>
  
              <div>
                <label class="block text-sm font-medium text-gray-700">Tip activitate</label>
                <input
                  disabled
                  v-model="form.type"
                  type="text"
                  class="mt-1 block w-full bg-gray-100 border-gray-300 rounded-md shadow-sm"
                />
              </div>
  
              <div>
                <label class="block text-sm font-medium text-gray-700">Scor activitate</label>
                <input v-model="form.score" type="number" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
              </div>
  
              <div class="flex items-center mt-8">
                <input
                  type="checkbox"
                  v-model="form.is_published"
                  class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                />
                <label class="ml-2 block text-sm text-gray-700">Publicat</label>
              </div>
            </div>
  
            <div class="mt-6">
              <label class="block text-sm font-medium text-gray-700">Scurtă descriere</label>
              <textarea
                v-model="form.description"
                rows="3"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
              ></textarea>
            </div>
  
            <!-- Dynamic Form Component Based on Type -->
            <div class="mt-6 border-t pt-6">
              <EditDietForm
                v-if="form.type === 'diet'"
                :details="form.details"
                :available-products="products"
              />
  
              <EditArticleForm
                v-if="form.type === 'article'"
                :details="form.details"
              />
  
              <EditTipForm
                v-if="form.type === 'tip'"
                :details="form.details"
              />
            </div>
  
            <div class="mt-8 text-right">
              <button v-if="form.isDirty"
                type="submit"
                class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md shadow-sm hover:bg-indigo-500"
              >
                💾 Salvează
              </button>
            </div>
          </form>
        </div>
      </div>
    </AuthenticatedLayout>
  </template>
  
  <script setup>
  import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
  import EditDietForm from "./EditDietForm.vue";
  import EditArticleForm from "./EditArticleForm.vue";
  import EditTipForm from "./EditTipForm.vue";
  import { useForm } from "@inertiajs/vue3";
  
  const props = defineProps({
    activity: Object,
    products: Array
  });
  
  const form = useForm({
    title: props.activity.title,
    type: props.activity.type,
    score: props.activity.score,
    is_published: props.activity.is_published,
    description: props.activity.description,
    details: props.activity.details || {}
  });
  
  const submit = () => {
    form.put(route("admin-gamification.activities.update", { id: props.activity.id }));
  };
  </script>
  