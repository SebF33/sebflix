// ReadAndSpeech
class ReadAndSpeechNotAVoice extends Error {
  constructor(msg) {
    super(msg);
    this.name = "ReadAndSpeechNotAVoice";
  }
}

class ReadAndSpeechNotSupported extends Error {
  constructor(msg) {
    super(msg);
    this.name = "ReadAndSpeechNotSupported";
  }
}

class ReadAndSpeech {
  options = {
    translateEmoji: false,
  };

  constructor(option) {
    if (!("speechSynthesis" in window)) {
      throw new ReadAndSpeechNotSupported(
        "speechSynthesis is not supported on your browser"
      );
    }

    this.options = { ...this.options, ...option };

    this.awaitVoices = new Promise(
      (done) => (window.speechSynthesis.onvoiceschanged = done)
    );
    this.synthesis = new SpeechSynthesisUtterance();
  }

  speak(msg) {
    msg = this.parse(msg);

    this.synthesis.text = msg;

    return window.speechSynthesis.speak(this.synthesis);
  }

  async speakers() {
    const voices = await this.awaitVoices;

    return window.speechSynthesis.getVoices();
  }

  changeVoice(voice) {
    try {
      this.synthesis.voice = voice;
    } catch (e) {
      throw new ReadAndSpeechNotAVoice(
        "voice parameter is not a SpeechSynthesisVoice"
      );
    }
  }

  currentVoice() {
    return this.synthesis.voice;
  }

  parse(msg) {
    if (!this.options.translateEmoji) {
      return msg;
    }
    return msg
      .split(" ")
      .map((word) => this.textEmojiParser.parse(word))
      .join(" ");
  }

  get options() {
    return this.options;
  }
}

const readAndSpeech = new ReadAndSpeech(); // Initialise reader
var synopsis = document.querySelector(".tsynopsis").textContent;
console.log(synopsis);

document.querySelector(".readandspeech").addEventListener("click", (e) => {
  e.preventDefault();
  readAndSpeech.speak(synopsis);
});
