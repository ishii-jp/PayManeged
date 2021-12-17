<template>
    <div>
        <form>
            <div class="form-group">
                <label for="year">お支払い入力年度</label>
                <select v-model="year" class="form-control" id="year">
                    <option v-for="(year, index) in parse(years)" :key="index">{{ year }}</option>
                </select>
            </div>
            <div class="form-group">
                <label for="year">お支払い入力月</label>
                <select v-model="month" class="form-control" id="year">
                    <option v-for="(month, index) in parse(months)" :key="index">{{ month }}</option>
                </select>
            </div>
        </form>
        <a :href="`${route}/payment/${year}/${month}`"
           class="btn btn-primary btn-lg"
           tabindex="-1"
           role="button"
           aria-disabled="true"
        >
            {{ year }}年{{ month }}月の支払いを入力
        </a>
    </div>
</template>

<script>
export default {
    data: function () {
        return {
            year: this.getNowYear(),
            month: this.getNowMonth()
        };
    },
    props: [
        "years",
        "months",
        "route"
    ],
    methods: {
        parse: function (value) {
            return JSON.parse(value);
        },
        getNowYear: function () {
            let date = new Date();
            return date.getFullYear();
        },
        getNowMonth: function () {
            let date = new Date();
            return date.getMonth() + 1;
        }
    }
};
</script>
