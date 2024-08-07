describe('API Call', () => {
  test('fetch advice count', async () => {
    const response = await fetch('http://localhost:8000/api/advice/count');
    expect(response.status).toBe(200);
  });

  test('fetch advice', async () => {
    const response = await fetch('http://localhost:8000/api/advice/1');
    expect(response.status).toBe(200);
  });

  test('fetch habitats', async () => {
    const response = await fetch('http://localhost:8000/api/habitat');
    expect(response.status).toBe(200);
  });

  test('fetch animals', async () => {
    const response = await fetch('http://localhost:8000/api/habitat/savane');
    expect(response.status).toBe(200);
  });

  test('fetch services', async () => {
    const response = await fetch('http://localhost:8000/api/service/');
    expect(response.status).toBe(200);
  });
});
