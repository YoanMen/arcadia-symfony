/**
 * @jest-environment jsdom
*/
document.body.innerHTML = `
<div>
<div class="cards-container-js"></div>
<button class="pagination-next-js"></button>
<button class="pagination-previous-js"></button>
<button class="pagination-double-upper-js"></button>
<button class="pagination-upper-js"></button>
</div>`

import CardsManager from "../../assets/scripts/cardsManager";
import { Card } from "../../assets/scripts/card";

const card = new Card('/path/image', 'alt image', 'href/link', 'name', 'description', '');
const manager = new CardsManager('/api/habitat', '/habitats', '/images/habitats/');

test("creation of card", () => {

  const articleElement = manager.createCard(card);
  expect(articleElement.innerHTML).not.toBe('');
});

