<template>
  <div class="container">
    <div class="panel panel-default">
      <div class="panel-heading pt-16">GuraEat</div>
    </div>
  </div>
</template>
<script>
export default {
  props: {},
  mounted() {
    if (this.$store.getters.getMode == 0) {
      this.$router.push("/login");
    } else if (this.$store.getters.getMode == 1) {
      this.$router.push("/customer/home");
    } else if (this.$store.getters.getMode == 2) {
      this.$router.push("/login");
    } else if (this.$store.getters.getMode == 3) {
      this.$router.push("/login");
    } else {
      this.$router.push("/login");
    }
  },
  data: () => ({}),
  methods: {},
};
</script>
<style>
</style>
