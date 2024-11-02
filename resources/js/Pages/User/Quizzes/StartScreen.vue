<!-- StartScreen.vue -->
<template>
  <div class="start-screen p-4 bg-gray-100 rounded-lg shadow-md">
    <h2 class="text-lg font-bold mb-2">Instrucțiuni pentru Quiz</h2>
    <p class="mb-4">
      Acest quiz conține un număr de <strong>{{ quiz.questions.length }}</strong> întrebări. Fiecare întrebare are un
      timp limitat de 30 de secunde.
    </p>
    <p class="mb-4">
      La final, vei vedea:
    </p>
    <ul class="list-disc list-inside mb-4">
      <li>Procentajul de corectitudine: câte întrebări ai răspuns corect din total.</li>
      <li>Punctele acumulate în urma răspunsurilor tale.</li>
    </ul>
    <p class="mb-4">
      Punctele vor fi adăugate în contul tău dacă alegi să <strong>închizi quiz-ul</strong> și nu mai dorești să-l retry
      pentru rezultate mai bune.
    </p>
    <p>
      Ai la dispoziție <strong>3 încercări</strong>, după care quiz-ul va deveni <strong>încuiat</strong>.
    </p>
    <p>Mai ai la dispozitie numai <strong>{{ this.remaining_attempts }}</strong> incercari !</p>
    <button @click="$emit('start-quiz')" class="bg-blue-500 text-white py-2 px-4 rounded">Start Quiz</button>
  </div>
</template>

<script>
export default {
  props: {
    quiz: Object,
    nr_attempts: Number
  },
  data() {
    return {
      remaining_attempts: 0,
    }
  },
  methods: {
  },
  mounted() {
   
    this.remaining_attempts = 3 - this.$page.props.nr_attempts;
    console.log(this.remaining_attempts);
  }
};
</script>

<style scoped></style>