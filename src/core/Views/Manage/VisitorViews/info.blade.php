<template>
    <app-dialog title="流量统计">
        <div class="p-4">
            <a-radio-group type="button" v-model:model-value="day" @change="changeZoom">
                <a-radio value="7">7天</a-radio>
                <a-radio value="30">30天</a-radio>
                <a-radio value="90">90天</a-radio>
                <a-radio value="365">1年内</a-radio>
            </a-radio-group>
            <div class="mt-6">
                {!! $appChart !!}
            </div>
        </div>
    </app-dialog>
</template>

<script>
  export default {
    data() {
      return {
        day: "7"
      }
    },
    mounted() {
      this.$nextTick(() => {
        this.changeZoom()
      })

    },
    methods: {
      changeZoom() {
        let now = new Date()
        this.onZoom(new Date(now.getTime() - this.day * 24 * 3600 * 1000).getTime(), now.getTime())
      },
      onZoom(start, stop) {
        this.$refs.chart.zoomX(
          start,
          stop
        )

      }

    }
  }
</script>
