<script>

import { Overlay } from 'trading-vue-js'

export default {
    name: 'Square',
    mixins: [Overlay],
    methods: {
        meta_info() {
            return { author: 'MajesticBastard', version: '1.0.0' }
        },

        draw(ctx) {
          	// Map the coordinates
          	let x = this.layout.t2screen(this.sett.t)
            let y = this.layout.$2screen(this.sett.$)
            ctx.strokeStyle = this.color
            ctx.beginPath()
            ctx.fillStyle = this.back
  			ctx.rect(x, y, 150, 150)
          	ctx.fillRect(x, y, 150, 150)
          	ctx.stroke()
          	ctx.fillStyle = this.color
          	ctx.font = "16px Arial";

 			// Helper labels
          	ctx.fillText(
              "z-index: " + this.sett['z-index'],
              x + 10, y + 20
            )
          	if ('legend' in this.sett) {
            	ctx.fillText(
                  "legend: " + this.sett.legend,
                  x + 10, y + 40
                )
            }
        },
      	// TVJS applies the overlay by this type:
        use_for() { return ['Square'] },
        data_colors() { return [this.color] }
    },
    // Helper refs to the setting object here
    computed: {
        sett() {
            return this.$props.settings
        },
      	back() {
          	return this.$props.colors.colorBack
        },
        color() {
            return this.sett.color
        }
    }
}
</script>