<template>
  <v-app>
    <v-container
      class="error-page fill-height"
      tag="section"
    >
      <v-row
        class="text-center"
        justify="center"
      >
        <v-col cols="auto">
          <h1 class="title font-weight-black" style="font-size: 9rem !important;">
            404
          </h1>

          <div class="display-3 mb-5 mt-10">
            Page not found :(
          </div>

          <v-btn
            depressed
            @click="$router.back()"
            color="primary"
          >
            Get me out of here!
          </v-btn>
        </v-col>
      </v-row>
    </v-container>
  </v-app>
</template>

<script>
  export default {
    name: '404',
  }
</script>

