
/**
 * @jest-environment jsdom
*/
document.body.innerHTML = `
<div>
<div class="advice-container-js"></div>
<button class="advice-previous-js"></button>
<button class="advice-next-js"></button>
</div>`

const getAdvice = require("../../assets/scripts/adviceManager.js");
const getAdvicesCount = require("../../assets/scripts/adviceManager.js");

test('fetch advices count and test if is not null', () => {
  const response = getAdvicesCount();
  expect(response).not.toBeNull();
})

test('fetch advices and test if is not null', () => {
  const response = getAdvice();
  expect(response).not.toBeNull();
})