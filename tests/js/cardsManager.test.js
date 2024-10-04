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

test("card structure is correct", () => {
  const articleElement = manager.createCard(card);

  const anchorElement = articleElement.querySelector('a');
  expect(anchorElement).not.toBeNull();
  expect(anchorElement.href).toContain(card.getHref());

  const imageElement = articleElement.querySelector('img');
  expect(imageElement).not.toBeNull();
  expect(imageElement.src).toContain(card.getImageName());
  expect(imageElement.alt).toBe(card.getAlt());

  const titleElement = articleElement.querySelector("h3");
  expect(titleElement).not.toBeNull();
  expect(titleElement.textContent).toBe(card.getTitle());

  const descriptionElement = articleElement.querySelector("p");
  expect(descriptionElement).not.toBeNull();
  expect(descriptionElement.textContent).toBe(card.getDescription());

});

