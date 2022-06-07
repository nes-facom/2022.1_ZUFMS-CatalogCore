import express from "express";
import { createClient } from "redis";
import cors from "cors";

const config = {
  port: process.env["REDIS_ADAPTER_PORT"] ?? 3030,
  redisUrl:
    process.env["REDIS_CONNECTION_URL"] ?? "redis://:secret@localhost:6379",
};

const client = createClient({
  url: config.redisUrl,
});

client.on("error", (err) => console.error("Redis Client Error", err));

await client.connect();

const app = express();

app.use(cors());
app.use(express.json());

app.use((err, req, res, next) => {
  res.status(500).json({
    error: err.name,
    message: err.message,
    stack: err.stack,
  });
});

app.get("/:state", async (req, res) => {
  const { state } = req.params;

  const email = await client.get(state);

  res.json({ state, email });
});

app.post("/:state", async (req, res) => {
  const { state } = req.params;
  const { email } = req.body;

  await client.set(state, email);

  res.json({ state, email });
});

app.listen(config.port, () => {
  console.log(`Example app listening on port ${config.port}`);
});
