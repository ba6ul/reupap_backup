@props([
    'name'     => '',
    'value'    => 100,
    'minValue' => 100,
    'step' => 100,
])

<v-quantity-changer
    {{ $attributes->merge(['class' => 'flex items-center border border-navyBlue']) }}
    name="{{ $name }}"
    value="{{ $value }}"
    min-value="{{ $minValue }}"
    step="{{ $step }}"
>
</v-quantity-changer>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-quantity-changer-template"
    >
        <div>
            <span 
                class="icon-minus cursor-pointer text-2xl"
                role="button"
                tabindex="0"
                aria-label="@lang('shop::app.components.quantity-changer.decrease-quantity')"
                @click="decrease"
            >
            </span>

            <p class="w-12 select-none text-center max-sm:text-sm">
                @{{ quantity }}
            </p>
            
            <span 
                class="icon-plus cursor-pointer text-2xl"
                role="button"
                tabindex="0"
                aria-label="@lang('shop::app.components.quantity-changer.increase-quantity')"
                @click="increase"
            >
            </span>

            <v-field
                type="hidden"
                :name="name"
                v-model="quantity"
            ></v-field>
        </div>
    </script>

    <script type="module">
    app.component("v-quantity-changer", {
        template: '#v-quantity-changer-template',

        props:['name', 'value', 'minValue','step'],

        data() {
            return  {
                quantity: parseInt(this.value) >= 100 ? parseInt(this.value) : 100, // start at 100
                currentStep: this.step ? Number(this.step) : 100,
                min: this.minValue ? Number(this.minValue) : 100,
            }
        },

        watch: {
            value() {
                this.quantity = Number(this.value);
            },
        },

        methods: {
            increase() {
                this.quantity = Number(this.quantity) + Number(this.currentStep);
                this.$emit('change', this.quantity);
            },

            decrease() {
                if (this.quantity > this.min) {
                    this.quantity = Number(this.quantity) - this.currentStep;
                    if (this.quantity < this.min) this.quantity = this.min;
                    this.$emit('change', this.quantity);
                }
            },
        },
    });
</script>

@endpushOnce
