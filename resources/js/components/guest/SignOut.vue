<template>
  <div>
    <div class="panel-heading text-center">Info</div>
  </div>
</template>

<script>
export default {
  props: {},
  mounted() {
    if (this.$store.getters.getAccessToken != "") {
      this.$store.commit("ACCESS_TOKEN", "");
      this.$store.commit("USER_NAME", "");
      this.$store.commit("MODE", 0);
      this.$router.push("/login");
    } else {
      this.$router.push("/login");
    }
  },
  data: () => ({}),
  methods: {},
};
</script>