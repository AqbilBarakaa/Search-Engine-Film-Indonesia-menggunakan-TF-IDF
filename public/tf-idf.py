import re, sys, pickle, math
import pandas as pd

if len(sys.argv) != 3:
    print("\nUse: python tf-idf.py [data.json] [output]\n")
    sys.exit(1)

input_json, output_data = sys.argv[1], sys.argv[2]
df = pd.read_json(input_json)

with open("stopword.txt", "r", encoding="utf-8") as f:
    stopwords = set(f.read().splitlines())

def clean_str(text):
    if not isinstance(text, str):
        return ""
    text = (text.encode('ascii', 'ignore')).decode("utf-8")
    text = re.sub("&.*?;", "", text)
    text = re.sub(">", "", text)
    text = re.sub("-", " ", text)
    text = re.sub(r"[\]\|\[\@\,\$\%\*\&\\\(\)\":]", "", text)
    text = re.sub(r"\.+", "", text)
    text = re.sub(r"^\s+", "", text)
    return text.lower()

df_data, tf_data, idf_data = {}, {}, {}
    
for i, row in df.iterrows():
    tf = {}
    combined = f"{row.get('judul','')} {row.get('sinopsis','')}"
    words = clean_str(combined).split()
    for w in words:
        if w and w not in stopwords:
            tf[w] = tf.get(w, 0) + 1
            df_data[w] = df_data.get(w, 0) + 1
    tf_data[i] = tf

for w, dfreq in df_data.items():
    idf_data[w] = 1 + math.log10(len(tf_data) / dfreq)

tf_idf = {}
for w in df_data:
    docs = []
    for idx, row in df.iterrows():
        tfv = tf_data[idx].get(w, 0)
        weight = tfv * idf_data[w]
        if weight > 0:
            docs.append({
                'id': idx,
                'judul': row.get('judul',''),
                'tahun': row.get('tahun',''),
                'sinopsis': row.get('sinopsis',''),
                'genre': row.get('genre',''),
                'batas_usia': row.get('batas_usia',''),
                'rating_film': row.get('rating_film',''),
                'votes': row.get('votes',''),
                'bahasa': row.get('bahasa',''),
                'sutradara': row.get('sutradara',''),
                'aktor': row.get('aktor',''),
                'durasi': row.get('durasi',''),
                'score': weight
            })
    tf_idf[w] = docs

with open(output_data, 'wb') as f:
    pickle.dump(tf_idf, f)
