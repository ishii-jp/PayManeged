<template>
  <div>
    <form :action="route" method="POST">
      <input type="hidden" name="_token" :value="csrf" />

      <div class="mb-3" v-for="category in categories" :key="category.id">
        <label :for="`payment[${category.id}]`" class="form-label">{{
          category.name
        }}</label>
        <input
          type="text"
          class="form-control"
          :id="`payment[${category.id}]`"
          :name="`payment[${category.id}]`"
          v-model="payment[category.id]"
        />
        <div v-if="hasError(errors, category.id)">
          <strong class="error" style="color: red">{{
            errors[`payment.${category.id}`][0]
          }}</strong>
        </div>
      </div>

      <label for="paymentSum" class="form-label">合計</label>
      <input
        type="text"
        readonly
        class="form-control-plaintext"
        id="paymentSum"
        name="paymentSum"
        :value="paymentSum"
      />

      <button type="submit" class="btn btn-primary">確認</button>
    </form>
  </div>
</template>

<script>
export default {
  data: function () {
    return {
      csrf: document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content"),
      payment: this.setPayment(),
    };
  },
  props: ["route", "categories", "old", "errors"],
  mounted() {
    console.log("PaymentFormComponent mounted.");
  },
  methods: {
    /**
     * paymentが存在する場合はidのキーの値を返します。
     * paymentがundefinedでない場合は初期値0を返します。
     *
     * @param String payment フォーム入力値
     * @param String id　カテゴリーID
     * @return String 判定した結果
     */
    setOldOrDefVal: function (payment, id) {
      if (payment !== undefined) {
        return payment[id];
      }
      return "0";
    },
    /**
     * カテゴリーごとの金額入力値の連想配列を作成して返します。
     *
     * @return Object カテゴリーごとの金額入力値の連想配列
     */
    setPayment: function () {
      let retArr = {};

      this.categories.forEach((value) => {
        retArr[value.id] = this.setOldOrDefVal(this.old.payment, value.id);
      });

      return retArr;
    },
    /**
     * エラーがあるか判定して返します。
     *
     * @param object errors エラー文言
     * @param String id カテゴリーID
     * @return Boolean true:エラーあり false エラーなし
     */
    hasError: function (errors, id) {
      if (errors[`payment.${id}`] === undefined) {
        return false;
      }
      return true;
    },
  },
  computed: {
    /**Ï
     * カテゴリーIDごとの金額の配列を作成し、配列の要素が数字か判定後Number型にキャストして合計金額を計算します。
     */
    paymentSum: function () {
      let paymentArr = this.categories.map((value) => {
        let retArr = [];
        retArr.push(this.payment[value.id]);
        return retArr;
      });

      return paymentArr.reduce(function (a, b) {
        let calcA;
        let calcB;
        if (!isNaN(a)) {
          calcA = Number(a);
        } else {
          calcA = 0;
        }

        if (!isNaN(b)) {
          calcB = Number(b);
        } else {
          calcB = 0;
        }

        return calcA + calcB;
      });
    },
  },
};
</script>
