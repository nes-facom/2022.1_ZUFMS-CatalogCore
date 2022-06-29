export const jsonToCSV = (data: any[]): string => {
  const replacer = (_: string, value: any) => (value === null ? "" : value);
  const header = Object.keys(data?.[0] ?? {});
  const csv = [
    header.join(","),
    ...data.map((row) =>
      header
        .map((fieldName) => JSON.stringify(row[fieldName], replacer))
        .join(",")
    ),
  ].join("\r\n");

  return csv;
};
