const express = require("express");
const bodyParser = require("body-parser");
const cors = require("cors");
const { createClient } = require("@supabase/supabase-js");

require("dotenv").config();

const app = express();
app.use(bodyParser.json());
app.use(cors());

const PORT = process.env.PORT || 3000;

const supabaseUrl = process.env.SUPABASE_URL;
const supabaseKey = process.env.SUPABASE_KEY;
const supabase = createClient(supabaseUrl, supabaseKey);

const readProducts = () => {
  return JSON.parse(fs.readFileSync(productsFile));
};

const writeProducts = (products) => {
  fs.writeFileSync(productsFile, JSON.stringify(products, null, 2));
};
app.get("/", (req, res) => {
  res.send(
    "<h1>Welcome to the Product CRUD API</h1><p>Use the available endpoints to interact with the API.</p>"
  );
});

app.get("/api/products", async (req, res) => {
  const { data: products, error } = await supabase.from("products").select("*");

  if (error) {
    return res.status(500).json({ message: error.message });
  }

  res.json(products);
});

app.get("/api/products/:id", async (req, res) => {
  const { id } = req.params;
  const { data: product, error } = await supabase
    .from("products")
    .select("*")
    .eq("id", id)
    .single();

  if (error) {
    return res.status(404).json({ message: "Product not found" });
  }

  res.json(product);
});

app.post("/api/products", async (req, res) => {
  const { name, price, image } = req.body;

  const { data: newProduct, error } = await supabase
    .from("products")
    .insert([{ name, price, image }])
    .single();

  if (error) {
    return res.status(500).json({ message: error.message });
  }

  res.status(201).json(newProduct);
});

app.put("/api/products/:id", async (req, res) => {
  const { id } = req.params;
  const { name, price, image } = req.body;

  const { data: updatedProduct, error } = await supabase
    .from("products")
    .update({ name, price, image })
    .eq("id", id)
    .single();

  if (error) {
    return res.status(500).json({ message: error.message });
  }

  res.json(updatedProduct);
});

app.delete("/api/products/:id", async (req, res) => {
  const { id } = req.params;

  const { error } = await supabase.from("products").delete().eq("id", id);

  if (error) {
    return res.status(500).json({ message: error.message });
  }

  res.json({ message: "Product deleted" });
});

app.get("/api/export/xml", async (req, res) => {
  try {
    const { data: products, error } = await supabase
      .from("products")
      .select("*");

    if (error) {
      throw error;
    }

    if (!products || products.length === 0) {
      throw new Error("No products found");
    }

    let xml = '<?xml version="1.0" encoding="UTF-8"?><products>';
    products.forEach((product) => {
      xml += `<product><id>${product.id}</id><name>${
        product.name
      }</name><price>${product.price}</price><image>${
        product.image || ""
      }</image></product>`;
    });
    xml += "</products>";

    res.header("Content-Type", "application/xml");
    res.header("Content-Disposition", "attachment; filename=products.xml");
    res.send(xml);
  } catch (error) {
    console.error("Error exporting to XML:", error.message);
    res
      .status(500)
      .json({ message: "Internal Server Error", error: error.message });
  }
});

app.get("/api/export/csv", async (req, res) => {
  try {
    const { data: products, error } = await supabase
      .from("products")
      .select("*");

    if (error) {
      throw error;
    }

    if (!products || products.length === 0) {
      throw new Error("No products found");
    }

    let csv = "id,name,price,image\n";
    products.forEach((product) => {
      csv += `${product.id},${product.name},${product.price},${
        product.image || ""
      }\n`;
    });

    res.header("Content-Type", "text/csv");
    res.attachment("products.csv");
    res.send(csv);
  } catch (error) {
    console.error("Error exporting to CSV:", error);
    res
      .status(500)
      .json({ message: "Internal Server Error", error: error.message });
  }
});

app.listen(PORT, () => {
  console.log(`Server is running on http://localhost:${PORT}`);
});
